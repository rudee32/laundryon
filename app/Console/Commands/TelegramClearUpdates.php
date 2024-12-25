<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;

class TelegramClearUpdates extends Command
{
    protected $signature = 'telegram:clear-updates';
    protected $description = 'Clear Telegram bot updates';

    public function handle(TelegramService $telegram)
    {
        if ($telegram->clearUpdates()) {
            $this->info('Updates cleared successfully');
        } else {
            $this->error('Failed to clear updates');
        }
    }
} 