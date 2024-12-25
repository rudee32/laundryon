<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            // Hapus kolom estimated_time jika ada
            if (Schema::hasColumn('services', 'estimated_time')) {
                $table->dropColumn('estimated_time');
            }
            
            // Tambah kolom estimated_days
            $table->integer('estimated_days')->after('price_per_kg');
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('estimated_days');
        });
    }
}; 