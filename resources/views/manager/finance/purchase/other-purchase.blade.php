<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Category') }}
            </h2>
            <x-button href="{{ route('manager.categories.create') }}" variant="success"
                class="justify-center max-w-xl gap-2">
                <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
                <span>Tambah Kategori</span>
            </x-button>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        {{ __("You're in page Category!") }}
    </div>

    <x-table.table>
        <x-slot name="header">
            <x-table.th class="px-28">Nama</x-table.th>
            <x-table.th>Jumlah Barang</x-table.th>
            <x-table.th>Dibuat Pada</x-table.th>
            <x-table.th class="px-16">Aksi</x-table.th>
        </x-slot>

        @foreach ($categories as $category)
            <x-table.tr>
                <x-table.td>
                    <form id="update-form-{{ $category->id }}" action="{{ route('manager.categories.update', $category->id) }}" method="POST" class="inline-flex">
                        @csrf
                        @method('PUT')
                        <x-form.input id="nama_kategori" class="block w-full" type="text"
                            name="nama_kategori" :value="old('nama_kategori', $category->name)" placeholder="{{ __('Nama Kategori') }}"
                            required autofocus />
                    </form>
                </x-table.td>
                <x-table.td class="px-10 md:px-16">
                    15
                </x-table.td>
                <x-table.td>
                    {{ $category->created_at->format('d M Y') }}
                </x-table.td>
                <x-table.td>
                    <button type="submit" form="update-form-{{ $category->id }}" class="text-indigo-600 hover:text-indigo-900">Update</button>
                    <form action="{{ route('manager.categories.destroy', $category->id) }}" method="POST"
                        class="inline-block ml-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </x-table.td>
            </x-table.tr>
        @endforeach
    </x-table.table>

    {{ $categories->links() }}
</x-app-layout>