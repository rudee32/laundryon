<div class="mb-4">
    <x-input-label for="telegram_id" :value="__('Telegram ID')" />
    <x-text-input id="telegram_id" type="text" name="telegram_id" 
        :value="old('telegram_id', $customer->telegram_id ?? '')" 
        class="mt-1 block w-full" />
    <p class="mt-1 text-sm text-gray-500">
        Untuk mendapatkan Telegram ID:
        1. Buka Telegram
        2. Chat ke @userinfobot
        3. Bot akan membalas dengan ID Anda
        4. Salin ID tersebut ke sini
    </p>
    <x-input-error :messages="$errors->get('telegram_id')" class="mt-2" />
</div> 