<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Sampah Data Barang') }}
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
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                        Nama barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                        Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                        Satuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                        Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="itemTable" class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-dark-eval-1">
                @foreach($deletedItem as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        {{ $item->code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden sm:table-cell">
                        {{ $item->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden sm:table-cell">
                        {{ $item->category->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden md:table-cell">
                        {{ $item->unit }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden md:table-cell">
                        Rp{{ number_format($item->purchase_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <div class="flex items-center space-x-4">
                            <form action="{{ route('manager.trash.items.restore', $item->id) }}" method="POST">
                                @csrf
                                <x-button type="submit"
                                    class="text-green-600 hover:text-green-900 dark:hover:text-green-400" size="sm">
                                    Restore
                                </x-button>
                            </form>
                            {{-- <form action="{{ route('manager.items.forceDelete', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                    Delete Permanently
                                </button> --}}
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    </div>

    @push('scripts')

    @endpush

</x-app-layout>