<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Models\Service;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

// Landing Page (Root URL)
Route::get('/', function () {
    $services = Service::all();
    return view('landing', compact('services'));
})->name('home');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Orders Routes
    Route::resource('orders', OrderController::class);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Customers Routes
    Route::resource('customers', CustomerController::class);
    
    // Services Routes
    Route::resource('services', App\Http\Controllers\ServiceController::class);
    
    // Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/export', [LaporanController::class, 'exportExcel'])->name('reports.export');
});

Route::get('/test-whatsapp', function () {
    return view('test-whatsapp');
})->middleware(['auth'])->name('test.whatsapp');

Route::post('/test-whatsapp', function (Request $request) {
    $whatsappService = app(App\Services\WhatsappService::class);
    $result = $whatsappService->sendMessage(
        $request->phone,
        "🔔 Ini adalah pesan test dari Laundry On.\n\nJika Anda menerima pesan ini, berarti konfigurasi WhatsApp sudah berhasil!"
    );
    
    return back()->with($result ? 'success' : 'error', 
        $result ? 'Pesan berhasil dikirim!' : 'Gagal mengirim pesan. Cek log untuk detail.');
})->middleware(['auth'])->name('test.whatsapp.send');

Route::post('/test-telegram-send/{customer}', function (App\Models\Customer $customer) {
    $telegramService = app(App\Services\TelegramService::class);
    
    Log::info('Attempting to send test message to customer:', [
        'customer_name' => $customer->name,
        'telegram_username' => $customer->telegram_username
    ]);
    
    $result = $telegramService->sendMessage(
        $customer->telegram_username,
        "🔔 Ini adalah pesan test untuk {$customer->name}.\n\n" .
        "Jika Anda menerima pesan ini, berarti konfigurasi Telegram sudah berhasil!"
    );
    
    Log::info('Send test message result:', ['success' => $result]);
    
    return back()->with(
        $result ? 'success' : 'error',
        $result ? 'Pesan berhasil dikirim!' : 'Gagal mengirim pesan. Pastikan username benar dan pelanggan sudah chat ke bot.'
    );
})->middleware(['auth'])->name('test.telegram.customer');

Route::get('/telegram/clear-updates', function() {
    $telegram = app(App\Services\TelegramService::class);
    $result = $telegram->clearUpdates();
    
    Log::info('Clear updates result:', ['success' => $result]);
    
    return back()->with(
        $result ? 'success' : 'error',
        $result ? 'Updates berhasil dibersihkan' : 'Gagal membersihkan updates'
    );
})->middleware(['auth'])->name('telegram.clear-updates');

require __DIR__.'/auth.php';
