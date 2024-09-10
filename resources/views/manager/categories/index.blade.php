<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Category') }}
            </h2>
            <x-button href="{{ route('manager.categories.create') }}" variant="success" class="justify-center max-w-xl gap-2">
                <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
                <span>Tambah Kategori</span>
            </x-button>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        {{ __("You're in page Category!") }}
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-dark-eval-1 rounded-md">
            <thead>
                <tr>
                    <th class="px-28 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Barang</th>
                    <th class="px-16 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('manager.categories.update', $category->id) }}" method="POST" class="inline-flex">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $category->name }}" 
                                       class="border rounded px-2 py-1" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $category->created_at->format('d M Y') }}
                        </td>
                        <td class="px-10 md:px-16 py-4 whitespace-nowrap">
                            15
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button type="submit" class="text-indigo-600 hover:text-indigo-900">Update</button>
                            </form>

                            <form action="{{ route('manager.categories.destroy', $category->id) }}" method="POST" class="inline-block ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $categories->links() }}

</x-app-layout>
