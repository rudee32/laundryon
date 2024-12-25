<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Pesanan</h2>
                <a href="{{ route('orders.create') }}" class="bg-[#00B4D8] hover:bg-[#0096B4] text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    Tambah Pesanan
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">No Order</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Layanan</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->customer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->service->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md
                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' :
                                           ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' :
                                           'bg-yellow-100 text-yellow-800')) }}">
                                        {{ ucfirst($order->status) }}
                                        <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <div x-show="open" 
                                         @click.away="open = false"
                                         class="absolute z-10 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            @foreach(App\Models\Order::getStatuses() as $status)
                                                <form method="POST" action="{{ route('orders.updateStatus', $order) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                    <button type="submit" 
                                                        class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100"
                                                        {{ $order->status === $status ? 'disabled' : '' }}>
                                                        {{ ucfirst($status) }}
                                                    </button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('orders.edit', $order) }}" 
                                   class="text-blue-600 hover:text-blue-900">Edit</a>
                                <form action="{{ route('orders.destroy', $order) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout> 