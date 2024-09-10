<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Barang') }}
            </h2>
            <x-button target="" href="{{ route('manager.items.create') }}" variant="success"
                class="justify-center max-w-xl gap-2">
                <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
                <span>Tambah Barang</span>
            </x-button>
        </div>
    </x-slot>


    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        {{ __("You're in page Barang!")  }}
    </div>

    <x-table.table>
        <x-slot name="header">
            <x-table.th class="px-28">Nama</x-table.th>
            <x-table.th>Kode barang</x-table.th>
            <x-table.th>Tanggal Masuk Barang</x-table.th>
            <x-table.th>Stok/pcs</x-table.th>
            <x-table.th>Harga/pcs</x-table.th>
            <x-table.th>Kategori</x-table.th>
            <x-table.th>Gudang</x-table.th>
            <x-table.th class="px-16">Aksi</x-table.th>
        </x-slot>

        @foreach ($items as $item)
            <x-table.tr>
                <x-table.td>
                    <form id="update-form-{{ $item->id }}" action="{{ route('manager.categories.update', $item->id) }}" method="POST" class="inline-flex">
                        @csrf
                        @method('PUT')
                        <x-form.input id="nama_kategori" class="block w-full" type="text"
                            name="nama_kategori" :value="old('nama_kategori', $item->name)" placeholder="{{ __('Nama Kategori') }}"
                            required autofocus />
                    </form>
                </x-table.td>
                <x-table.td class="px-10 md:px-16">
                    {{ $item->code }}
                </x-table.td>
                <x-table.td>
                    {{ $item->created_at->format('d M Y') }}
                </x-table.td>
                <x-table.td class="px-10 md:px-16">
                    {{ $item->stok }}
                </x-table.td>
                <x-table.td class="px-10 md:px-16">
                    {{ number_format($item->price, 0,',','.') }}
                </x-table.td>
                <x-table.td class="px-10 md:px-16">
                    {{ $item->category->name }}
                </x-table.td>
                <x-table.td class="px-10 md:px-16">
                    {{ $item->warehouse->name }}
                </x-table.td>
                <x-table.td>
                    <button type="submit" form="update-form-{{ $item->id }}" class="text-indigo-600 hover:text-indigo-900">Update</button>
                    <form action="{{ route('manager.categories.destroy', $item->id) }}" method="POST"
                        class="inline-block ml-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </x-table.td>
            </x-table.tr>
        @endforeach
    </x-table.table>

</x-app-layout>