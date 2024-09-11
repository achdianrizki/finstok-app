<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Gudang') }}
            </h2>
            <x-button target="" href="{{ route('manager.warehouse.create') }}" variant="success"
                class="justify-center max-w-xl gap-2">
                <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
                <span>Tambah Gudang</span>
            </x-button>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        {{ __("You're in page Warehouse!") }}
    </div>

    <x-table.table class="min-w-full bg-white dark:bg-dark-eval-1 rounded-md">
        <x-slot name="header">
            <x-table.th class="px-28">Nama</x-table.th>
            <x-table.th class="px-24">Alamat</x-table.th>
            <x-table.th>Dibuat Pada</x-table.th>
            <x-table.th class="px-16">Aksi</x-table.th>
        </x-slot>

        @foreach ($warehouses as $warehouse)
            <x-table.tr>
                <x-table.td class="px-6 py-4 whitespace-nowrap">
                    <form id="update-form-{{ $warehouse->id }}"
                        action="{{ route('manager.warehouse.update', $warehouse->id) }}" method="POST"
                        class="inline-flex">
                        @csrf
                        @method('PUT')

                        <x-form.input id="name" class="block w-full min-w-[200px] p-2" type="text" name="name"
                            :value="old('name', $warehouse->name)" placeholder="{{ __('Nama gudang') }}" required autofocus />
                </x-table.td>
                <x-table.td class="px-6 py-4 whitespace-nowrap">
                    <x-form.input type="text" name="address" value="{{ $warehouse->address }}"
                        class="block w-full min-w-[200px] p-2" />
                </x-table.td>
                </form>

                <x-table.td class="px-6 py-4 whitespace-nowrap">
                    {{ $warehouse->created_at->format('d M Y') }}
                </x-table.td>

                <x-table.td class="px-6 py-4 whitespace-nowrap">
                    <button type="submit" form="update-form-{{ $warehouse->id }}"
                        class="text-indigo-600 hover:text-indigo-900">Update</button>
                    <form action="{{ route('manager.warehouse.destroy', $warehouse->id) }}" method="POST"
                        class="inline-block ml-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </x-table.td>
                
            </x-table.tr>
        @endforeach
    </x-table.table>

    {{ $warehouses->links() }}
</x-app-layout>
