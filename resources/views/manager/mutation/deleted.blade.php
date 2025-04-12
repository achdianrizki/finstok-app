<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                @section('title', __('Laporan Mutasi'))
                {{ __('Laporan Mutasi') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="flex flex-col md:flex-row md:justify-end gap-4 my-3">


            <!-- Search Input-->
            <div class="w-full md:w-auto">
                <input type="text" id="search" placeholder="Search items..."
                    class="rounded w-full md:w-auto px-4 py-2 dark:bg-dark-eval-1" name="search">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="export-table" class="min-w-full rounded-md">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 dark:bg-slate-900 dark:text-white text-sm leading-normal">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">No</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                            Tanggal Mutasi</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                            Dari Gudang</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                            Ke Gudang</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                            Jumlah </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemTable"
                    class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-dark-eval-1">
                    @foreach ($mutations as $mutation)
                    <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">{{ $mutation->mutated_at }}</td>
                        <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">{{ $mutation->fromWarehouse->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">{{ $mutation->toWarehouse->name
                            }}</td>
                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">{{ $mutation->qty }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('manager.trash.mutation.restore', $mutation->id) }}" method="POST"
                                class="inline">
                                @csrf
                                <x-button type="submit" size="sm"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    Restore
                                </x-button>
                            </form>
                            <form method="POST" action="/mutation/delete/{{ $mutation->id }}" class="delete-form"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <x-button type="button"
                                    class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 delete-button">
                                    <x-heroicon-o-trash class="w-3 h-3" aria-hidden="true" />
                                </x-button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#search').on('keyup', function () {
                    var value = $(this).val().toLowerCase();
                    $('#itemTable tr').filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });
            });
        </script>
    @endpush

    @push('styles')

    @endpush

</x-app-layout>