<?php

use App\Models\DepotIamArticle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('depot_iam_articles', function (Blueprint $table) {
            $table->string('statut', 20)->default('actif')->after('sortie');
            $table->string('etat', 20)->default('dispo')->after('statut');
        });

        DepotIamArticle::query()->each(function (DepotIamArticle $article) {
            $article->etat = $article->computeEtat();
            $article->saveQuietly();
        });
    }

    public function down(): void
    {
        Schema::table('depot_iam_articles', function (Blueprint $table) {
            $table->dropColumn(['statut', 'etat']);
        });
    }
};
