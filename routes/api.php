<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/telegram/webhook', function (Request $request) {
    Log::info('Telegram webhook hit:', $request->all());
    
    $update = $request->all();
    $message = $update['message'] ?? null;
    
    if (!$message) {
        return response()->json(['message' => 'No message data']);
    }

    // Handle commands
    $response = \App\Services\TelegramCommands::handleCommand($update);
    if ($response) {
        $telegram = app(\App\Services\TelegramService::class);
        $telegram->sendMessage(
            $message['from']['username'],
            $response
        );
    }

    // Update chat_id
    $username = $message['from']['username'] ?? null;
    $chatId = $message['chat']['id'] ?? null;
    
    if ($username && $chatId) {
        $customer = \App\Models\Customer::where('telegram_username', $username)->first();
        if ($customer) {
            $customer->update(['telegram_chat_id' => $chatId]);
            Log::info('Updated customer chat_id:', [
                'username' => $username,
                'chat_id' => $chatId
            ]);
        }
    }

    return response()->json(['message' => 'success']);
}); 