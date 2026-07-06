<?php

namespace App\Services;

use App\Models\BonAchatLigne;
use App\Models\DepotIamArticle;
use App\Models\Fournisseur;
use Illuminate\Support\Facades\DB;

class DepotIamService
{
    public static function syncFromBonAchats(): void
    {
        $iamIds = Fournisseur::iamIds();

        if (empty($iamIds)) {
            return;
        }

        $aggregated = BonAchatLigne::query()
            ->join('bons_achats', 'bons_achats.id', '=', 'bon_achat_lignes.bon_achat_id')
            ->whereIn('bons_achats.fournisseur_id', $iamIds)
            ->select([
                DB::raw("COALESCE(bon_achat_lignes.reference, '') as reference"),
                'bon_achat_lignes.designation',
                DB::raw('MAX(bon_achat_lignes.mesure) as mesure'),
                DB::raw('MAX(bon_achat_lignes.stock_initial) as stock_initial'),
                DB::raw('SUM(bon_achat_lignes.quantite) as entree'),
            ])
            ->groupBy(DB::raw("COALESCE(bon_achat_lignes.reference, '')"), 'bon_achat_lignes.designation')
            ->get();

        $seen = [];

        DB::transaction(function () use ($aggregated, &$seen) {
            foreach ($aggregated as $row) {
                $reference = trim((string) $row->reference);
                $key = $reference.'|'.$row->designation;
                $seen[] = $key;

                $article = DepotIamArticle::query()
                    ->where('reference', $reference)
                    ->where('designation', $row->designation)
                    ->first();

                if ($article) {
                    $article->update([
                        'mesure' => $row->mesure ?: $article->mesure,
                        'stock_initial' => (float) $row->stock_initial > 0 ? $row->stock_initial : $article->stock_initial,
                        'entree' => $row->entree,
                    ]);
                } else {
                    DepotIamArticle::create([
                        'reference' => $reference,
                        'designation' => $row->designation,
                        'mesure' => $row->mesure,
                        'stock_initial' => $row->stock_initial ?? 0,
                        'entree' => $row->entree,
                        'sortie' => 0,
                    ]);
                }
            }

            DepotIamArticle::all()->each(function (DepotIamArticle $article) use ($seen) {
                $key = ($article->reference ?? '').'|'.$article->designation;
                if (! in_array($key, $seen, true) && (float) $article->sortie <= 0 && (float) $article->stock_initial <= 0) {
                    $article->delete();
                } elseif (! in_array($key, $seen, true)) {
                    $article->update(['entree' => 0]);
                }
            });
        });
    }
}
