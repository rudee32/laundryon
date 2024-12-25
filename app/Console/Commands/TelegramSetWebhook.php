<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;

class TelegramSetWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {url?}';
    protected $description = 'Set Telegram bot webhook URL';

    public function handle(TelegramService $telegram)
    {
        $url = $this->argument('url') ?? config('app.url') . '/api/telegram/webhook';
        
        if ($telegram->setWebhook($url)) {
            $this->info('Webhook set successfully to: ' . $url);
        } else {
            $this->error('Failed to set webhook');
        }
    }
} 