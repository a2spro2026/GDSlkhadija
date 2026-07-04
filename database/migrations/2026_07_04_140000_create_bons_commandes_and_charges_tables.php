<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bons_commandes', function (Blueprint $table) {
            $table->id();
            $table->date('date_bon');
            $table->string('numero_bon')->unique();
            $table->string('client');
            $table->string('ville')->nullable();
            $table->string('adresse')->nullable();
            $table->decimal('montant', 12, 2)->default(0);
            $table->enum('statut', ['livre', 'en_attente', 'annule'])->default('en_attente');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->date('date_charge');
            $table->string('libelle');
            $table->decimal('montant', 12, 2);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charges');
        Schema::dropIfExists('bons_commandes');
    }
};
