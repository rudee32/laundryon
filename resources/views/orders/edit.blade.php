<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Edit Pesanan</h2>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('orders.update', $order) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer -->
                    <div>
                        <x-input-label for="customer_id" :value="__('Pelanggan')" />
                        <select id="customer_id" name="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#00B4D8] focus:ring focus:ring-[#00B4D8]/20" required>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                    </div>

                    <!-- Service -->
                    <div>
                        <x-input-label for="service_id" :value="__('Layanan')" />
                        <select id="service_id" name="service_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#00B4D8] focus:ring focus:ring-[#00B4D8]/20" required>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ $order->service_id == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                    </div>

                    <!-- Weight -->
                    <div>
                        <x-input-label for="weight" :value="__('Berat (kg)')" />
                        <x-text-input id="weight" type="number" step="0.1" name="weight" :value="old('weight', $order->weight)" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                    </div>

                    <!-- Status -->
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#00B4D8] focus:ring focus:ring-[#00B4D8]/20" required>
                            @foreach(App\Models\Order::getStatuses() as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <!-- Pickup Date -->
                    <div>
                        <x-input-label for="pickup_date" :value="__('Tanggal Pengambilan')" />
                        <x-text-input id="pickup_date" type="date" name="pickup_date" :value="old('pickup_date', $order->pickup_date)" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('pickup_date')" class="mt-2" />
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <x-input-label for="notes" :value="__('Catatan')" />
                        <textarea id="notes" name="notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#00B4D8] focus:ring focus:ring-[#00B4D8]/20" rows="3">{{ old('notes', $order->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 gap-4">
                    <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-900">
                        {{ __('Batal') }}
                    </a>
                    <x-primary-button>
                        {{ __('Simpan Perubahan') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 