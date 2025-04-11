<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Sampah Pembelian Barang') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="flex flex-col md:flex-row md:justify-end gap-4 my-3">
            <div class="w-full md:w-auto">
                <input type="text" id="search" placeholder="Search items..."
                    class=" rounded w-full md:w-auto px-4 py-2 dark:bg-dark-eval-1" name="search">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="export-table" class="min-w-full rounded-md">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 dark:bg-slate-900 dark:text-white text-sm leading-normal">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nomor Pembelian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">Tanggal Pembelian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">Nama Pemasok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-dark-eval-1">
                    @if ($deletedPurchases->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center">
                                Data tidak ditemukan.
                            </td>
                        </tr>
                    @else
                        @foreach ($deletedPurchases as $purchase)
                            <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="/manager/purchase/{{ $purchase->id }}" class="text-blue-500 hover:underline">{{ $purchase->purchase_number }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">{{ $purchase->purchase_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">{{ $purchase->supplier->name }}</td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">{{ $purchase->status === 'belum_lunas' ? 'Belum Lunas' : $purchase->status === 'lunas' ? 'Lunas' : $purchase->status }}</td> --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-button target="" href="/manager/purchase/{{ $purchase->id }}" variant="primary" size="sm" class="justify-center max-w-sm gap-2">
                                        Lihat
                                    </x-button>

                                    {{-- <form action="{{ route('manager.purchase.restore', ['id' => $purchase->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-green-800 text-white p-2 rounded">
                                            Restore
                                        </button>
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        


        

    </div>

    @push('scripts')

    @endpush

</x-app-layout>
