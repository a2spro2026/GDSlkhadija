<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bon_achat_lignes', function (Blueprint $table) {
            $table->string('mesure', 50)->nullable()->after('designation');
        });

        Schema::create('depot_iam_articles', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->default('');
            $table->string('designation');
            $table->string('mesure', 50)->nullable();
            $table->decimal('stock_initial', 12, 2)->default(0);
            $table->decimal('entree', 12, 2)->default(0);
            $table->decimal('sortie', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['reference', 'designation'], 'depot_iam_ref_desig_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('depot_iam_articles');

        Schema::table('bon_achat_lignes', function (Blueprint $table) {
            $table->dropColumn('mesure');
        });
    }
};
