<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Tambah kolom baru setelah kolom address
            $table->string('telegram_username')->nullable()->after('address');
            $table->string('telegram_chat_id')->nullable()->after('telegram_username');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Hapus kolom jika rollback
            if (Schema::hasColumn('customers', 'telegram_username')) {
                $table->dropColumn('telegram_username');
            }
            if (Schema::hasColumn('customers', 'telegram_chat_id')) {
                $table->dropColumn('telegram_chat_id');
            }
        });
    }
}; 