<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reglements', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->date('date_reglement');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->cascadeOnDelete();
            $table->enum('type_reglement', ['esp', 'chq', 'eff', 'vir', 'vers']);
            $table->string('numero')->nullable();
            $table->string('banque')->nullable();
            $table->decimal('montant', 12, 2);
            $table->string('nom_tire')->nullable();
            $table->date('date_decaissement')->nullable();
            $table->enum('statut', ['paye', 'impaye', 'reporte', 'devalide'])->default('paye');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('reglement_bon_achat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reglement_id')->constrained('reglements')->cascadeOnDelete();
            $table->foreignId('bon_achat_id')->constrained('bons_achats')->cascadeOnDelete();
            $table->decimal('montant_affecte', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reglement_bon_achat');
        Schema::dropIfExists('reglements');
    }
};
