<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Test Koneksi WhatsApp</h2>
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
                <h3 class="font-medium text-gray-700 mb-2">Status Konfigurasi:</h3>
                <ul class="list-disc list-inside space-y-2">
                    <li>
                        API Key: 
                        @if(config('services.fonnte.key'))
                            <span class="text-green-600">✓ Terkonfigurasi</span>
                        @else
                            <span class="text-red-600">✗ Belum dikonfigurasi</span>
                        @endif
                    </li>
                </ul>
            </div>

            <form method="POST" action="{{ route('test.whatsapp.send') }}" class="mt-6">
                @csrf
                
                <div class="max-w-xl">
                    <div class="mb-4">
                        <x-input-label for="phone" :value="__('Nomor WhatsApp untuk Test')" />
                        <x-text-input id="phone" type="text" name="phone" 
                            class="mt-1 block w-full" 
                            placeholder="Contoh: 081234567890"
                            required />
                        <p class="mt-1 text-sm text-gray-500">
                            Masukkan nomor dengan format: 08xxx atau 628xxx
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>
                            {{ __('Kirim Pesan Test') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>

            <div class="mt-8">
                <h3 class="font-medium text-gray-700 mb-2">Panduan Konfigurasi:</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                    <li>Daftar akun di <a href="https://fonnte.com" target="_blank" class="text-blue-600 hover:underline">Fonnte.com</a></li>
                    <li>Setelah login, dapatkan API key dari menu Device</li>
                    <li>Tambahkan API key ke file .env: <code class="bg-gray-100 px-2 py-1 rounded">FONNTE_API_KEY=your_api_key</code></li>
                    <li>Jalankan: <code class="bg-gray-100 px-2 py-1 rounded">php artisan config:clear</code></li>
                    <li>Test pengiriman pesan menggunakan form di atas</li>
                </ol>
            </div>
        </div>
    </div>
</x-app-layout> 