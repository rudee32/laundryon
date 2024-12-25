<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $token;
    protected $baseUrl;

    public function __construct()
    {
        $this->token = '7407673778:AAH5LOgUYyyXnBumxt0VrYQiQz6FtgH_Bu0';
        $this->baseUrl = "https://api.telegram.org/bot{$this->token}";
    }

    public function sendMessage($username, $message)
    {
        try {
            $username = ltrim($username, '@');
            
            // Coba dapatkan chat_id
            $chatId = $this->getChatId($username);
            if (!$chatId) {
                Log::error('Failed to get chat_id', ['username' => $username]);
                return false;
            }

            // Kirim pesan menggunakan chat_id
            $response = Http::withoutVerifying()
                ->post("{$this->baseUrl}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML'
                ]);

            Log::info('Send message response', $response->json());
            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Send message error', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    protected function getChatId($username)
    {
        try {
            // Coba ambil dari database dulu
            $customer = \App\Models\Customer::where('telegram_username', $username)->first();
            if ($customer && $customer->telegram_chat_id) {
                return $customer->telegram_chat_id;
            }

            // Jika tidak ada, coba ambil dari updates
            $response = Http::withoutVerifying()
                ->get("{$this->baseUrl}/getUpdates");
                
            if ($response->successful()) {
                $updates = $response->json()['result'] ?? [];
                foreach ($updates as $update) {
                    if (isset($update['message']['from']['username']) 
                        && strtolower($update['message']['from']['username']) === strtolower($username)) {
                        
                        $chatId = $update['message']['chat']['id'];
                        
                        // Update di database
                        if ($customer) {
                            $customer->update(['telegram_chat_id' => $chatId]);
                        }
                        
                        return $chatId;
                    }
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Get chat_id error', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function testBot()
    {
        try {
            $response = Http::withoutVerifying()
                ->get("{$this->baseUrl}/getMe");
            
            Log::info('Test bot response', $response->json());
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Test bot error', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function clearUpdates()
    {
        try {
            $response = Http::withoutVerifying()
                ->get("{$this->baseUrl}/getUpdates", [
                    'offset' => -1
                ]);
            
            Log::info('Clear updates response', $response->json());
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Clear updates error', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }
} 