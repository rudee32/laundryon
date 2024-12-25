<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Test Koneksi Telegram</h2>
        </div>
        
        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4">
                <h3 class="font-medium text-gray-700 mb-2">Panduan Mendapatkan Telegram ID:</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                    <li>Buka Telegram</li>
                    <li>Cari dan chat @userinfobot</li>
                    <li>Bot akan membalas dengan ID Anda</li>
                    <li>Salin ID tersebut ke form pelanggan</li>
                </ol>
            </div>

            <div class="mt-8">
                <h3 class="font-medium text-gray-700 mb-2">Panduan Konfigurasi Bot:</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                    <li>Buat bot baru melalui @BotFather di Telegram</li>
                    <li>Dapatkan token bot</li>
                    <li>Tambahkan token ke file .env: <code class="bg-gray-100 px-2 py-1 rounded">TELEGRAM_BOT_TOKEN=your_bot_token</code></li>
                    <li>Jalankan: <code class="bg-gray-100 px-2 py-1 rounded">php artisan config:clear</code></li>
                </ol>
            </div>
        </div>
    </div>
</x-app-layout> 