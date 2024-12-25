<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TelegramCommands
{
    public static function handleCommand($update)
    {
        $message = $update['message'] ?? null;
        if (!$message) return null;

        $text = $message['text'] ?? '';
        $username = $message['from']['username'] ?? '';

        switch ($text) {
            case '/start':
                return "🎉 Selamat datang di Bot Laundry On!\n\n" .
                       "Bot ini akan mengirimkan notifikasi status laundry Anda.\n" .
                       "Gunakan perintah /help untuk melihat bantuan.";

            case '/help':
                return "📌 Daftar Perintah:\n\n" .
                       "/start - Mulai menggunakan bot\n" .
                       "/help - Tampilkan bantuan\n\n" .
                       "ℹ️ Informasi:\n" .
                       "Bot ini akan mengirimkan notifikasi saat:\n" .
                       "- Pesanan laundry selesai\n" .
                       "- Status pesanan berubah\n" .
                       "- Informasi penting lainnya";

            default:
                return null;
        }
    }
} 