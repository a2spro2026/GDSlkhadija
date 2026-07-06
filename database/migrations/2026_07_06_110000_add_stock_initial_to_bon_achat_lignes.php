<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bon_achat_lignes', function (Blueprint $table) {
            $table->decimal('stock_initial', 12, 2)->default(0)->after('mesure');
        });
    }

    public function down(): void
    {
        Schema::table('bon_achat_lignes', function (Blueprint $table) {
            $table->dropColumn('stock_initial');
        });
    }
};
