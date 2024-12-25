<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Tambah Pelanggan</h2>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('customers.store') }}" class="max-w-xl">
                @csrf

                <!-- Nama -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Nama')" />
                    <x-text-input id="name" type="text" name="name" :value="old('name')" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- No. Telepon -->
                <div class="mb-4">
                    <x-input-label for="phone" :value="__('No. Telepon')" />
                    <x-text-input id="phone" type="text" name="phone" :value="old('phone')" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <!-- Telegram Username -->
                <div class="mb-4">
                    <x-input-label for="telegram_username" :value="__('Username Telegram')" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">@</span>
                        </div>
                        <x-text-input id="telegram_username" 
                            type="text" 
                            name="telegram_username" 
                            :value="old('telegram_username')" 
                            class="mt-1 block w-full pl-7"
                            placeholder="username" />
                    </div>
                    <div class="mt-1 text-sm text-gray-500">
                        <p class="mb-1">Untuk mengaktifkan notifikasi Telegram:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Pastikan pelanggan memiliki username Telegram</li>
                            <li>Minta pelanggan untuk chat ke bot @LaundryOnBot</li>
                            <li>Kirim pesan "/start" ke bot</li>
                            <li>Tunggu minimal 5 detik</li>
                            <li>Masukkan username Telegram pelanggan (tanpa @)</li>
                        </ol>
                        <p class="mt-2 text-red-600">
                            Penting: Bot harus menerima pesan dari pelanggan sebelum bisa mengirim notifikasi!
                        </p>
                    </div>
                    <x-input-error :messages="$errors->get('telegram_username')" class="mt-2" />
                </div>

                <!-- Alamat -->
                <div class="mb-6">
                    <x-input-label for="address" :value="__('Alamat')" />
                    <textarea id="address" name="address" rows="3" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#00B4D8] focus:ring focus:ring-[#00B4D8]/20" 
                        required>{{ old('address') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>
                        {{ __('Simpan') }}
                    </x-primary-button>
                    <a href="{{ route('customers.index') }}" class="text-gray-600 hover:text-gray-900">
                        {{ __('Batal') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 