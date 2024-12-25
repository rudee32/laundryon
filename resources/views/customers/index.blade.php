<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Pelanggan</h2>
                <a href="{{ route('customers.create') }}" class="bg-[#00B4D8] hover:bg-[#0096B4] text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    Tambah Pelanggan
                </a>
            </div>
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

            <div class="mb-4 flex justify-end">
                <a href="{{ route('telegram.clear-updates') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                   onclick="return confirm('Clear Telegram updates?')">
                    Clear Updates
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">No. Telepon</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Alamat</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Telegram ID</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total Pesanan</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($customers as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $customer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $customer->phone }}</td>
                            <td class="px-6 py-4 text-sm">{{ $customer->address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($customer->telegram_username)
                                    <a href="https://t.me/{{ $customer->telegram_username }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-900">
                                        @{{ $customer->telegram_username }}
                                    </a>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Terhubung
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $customer->orders_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('customers.edit', $customer) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Edit
                                </a>
                                
                                @if($customer->telegram_username)
                                    <form method="POST" 
                                          action="{{ route('test.telegram.customer', $customer) }}" 
                                          class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900"
                                                onclick="return confirm('Kirim pesan test ke {{ $customer->name }}?')">
                                            Test Telegram
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('customers.destroy', $customer) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($customers->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    Belum ada data pelanggan
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 