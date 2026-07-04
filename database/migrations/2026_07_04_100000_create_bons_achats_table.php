<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bons_achats', function (Blueprint $table) {
            $table->id();
            $table->date('date_bon');
            $table->string('numero_bon')->unique();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->cascadeOnDelete();
            $table->decimal('total', 12, 2)->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('bon_achat_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_achat_id')->constrained('bons_achats')->cascadeOnDelete();
            $table->string('reference')->nullable();
            $table->string('designation');
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_unitaire', 12, 2);
            $table->decimal('sous_total', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bon_achat_lignes');
        Schema::dropIfExists('bons_achats');
    }
};
