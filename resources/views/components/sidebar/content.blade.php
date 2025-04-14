@props(['warehouses', 'collapsible'])

<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>



    <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        Menu
    </div>

    <x-sidebar.dropdown title="Master" :active="request()->routeIs('manager.items*') ||
        request()->routeIs('manager.supplier*') ||
        request()->routeIs('manager.salesman*') ||
        request()->routeIs('manager.buyer*') ||
        request()->routeIs('manager.warehouses.index') ||
        request()->routeIs('manager.warehouses.create') ||
        request()->routeIs('manager.warehouses.edit')">
        <x-slot name="icon">
            <x-heroicon-o-rectangle-stack class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Pemasok Barang" href="{{ route('manager.supplier.index') }}"
            :active="request()->routeIs('manager.supplier*')" />
            
        <x-sidebar.sublink title="Barang" href="{{ route('manager.items.index') }}"
            :active="request()->routeIs('manager.items*')" />


        <x-sidebar.sublink title="Pelanggan" href="{{ route('manager.buyer.index') }}"
            :active="request()->routeIs('manager.buyer*')" />

        <x-sidebar.sublink title="Sales" href="{{ route('manager.salesman.index') }}"
            :active="request()->routeIs('manager.salesman*')" />

        <x-sidebar.sublink title="Gudang" href="{{ route('manager.warehouses.index') }}"
            :active="request()->routeIs('manager.warehouses.index')" />

    </x-sidebar.dropdown>

    <x-sidebar.dropdown title="Pembelian" :active="request()->routeIs('manager.purchase*') ||
        request()->routeIs('manager.outgoingpayment*') ||
        request()->routeIs('manager.return.purchase*') ||
        request()->routeIs('manager.trash.purchase')">
        <x-slot name="icon">
            <x-icons.supplier class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Pembelian Barang" href="{{ route('manager.purchase.index') }}"
            :active="request()->routeIs('manager.purchase*') || request()->routeIs('manager.trash.purchase')" />

        <x-sidebar.sublink title="Pelunasan Pembelian" href="{{ route('manager.outgoingpayment.index') }}"
            :active="request()->routeIs('manager.outgoingpayment*')" />

        <x-sidebar.sublink title="Retur Pembelian" href="{{ route('manager.return.purchase') }}"
            :active="request()->routeIs('manager.return.purchase*')" />

    </x-sidebar.dropdown>

    <x-sidebar.dropdown title="Penjualan" :active="request()->routeIs('manager.sales.*') ||
        request()->routeIs('manager.incomingpayment*') ||
        request()->routeIs('manager.return.sale*')">
        <x-slot name="icon">
            <x-icons.sale-tag class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Penjualan Barang" href="{{ route('manager.sales.index') }}"
            :active="request()->routeIs('manager.sales.*')" />

        <x-sidebar.sublink title="Pelunasan Penjualan" href="{{ route('manager.incomingpayment.index') }}"
            :active="request()->routeIs('manager.incomingpayment*')" />

        <x-sidebar.sublink title="Retur Penjualan" href="{{ route('manager.return.sale') }}"
            :active="request()->routeIs('manager.return.sale*')" />

    </x-sidebar.dropdown>

    <x-sidebar.dropdown title="Informasi Gudang" :active="request()->routeIs('manager.warehouses.show')">
        <x-slot name="icon">
            <x-icons.warehouse class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        @foreach ($warehouses as $warehouse)
        <x-sidebar.sublink title="{{ $warehouse->name }}"
            href="{{ route('manager.warehouses.show', $warehouse->slug) }}" :active="request()->routeIs('manager.warehouses.show') &&
            request()->route('warehouse')?->slug === $warehouse->slug" class="relative">
        </x-sidebar.sublink>
        @endforeach

    </x-sidebar.dropdown>

    <x-sidebar.dropdown title="Opname Gudang" :active="request()->routeIs('manager.warehouses.opname')">
        <x-slot name="icon">
            <x-heroicon-o-adjustments-horizontal class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        @foreach ($warehouses as $warehouse)
        <x-sidebar.sublink title="{{ $warehouse->name }}"
            href="{{ route('manager.warehouses.opname', $warehouse->slug) }}" :active="request()->routeIs('manager.warehouses.opname') &&
                    request()->route('warehouse')?->slug == $warehouse->slug" class="relative">
        </x-sidebar.sublink>
        @endforeach
    </x-sidebar.dropdown>

    <x-sidebar.dropdown title="Report" :active="request()->routeIs('manager.report*')">
        <x-sidebar.sublink title="Laporan Pembelian" href="{{ route('manager.report.purchase') }}"
            :active="request()->routeIs('manager.report.purchase')" />

        <x-sidebar.sublink title="Laporan Penjualan" href="{{ route('manager.report.sale') }}"
            :active="request()->routeIs('manager.report.sale')" />

        <x-slot name="icon">
            {{--
            <x-icons.report class="flex-shrink-0 w-6 h-6" aria-hidden="true" /> --}}
            <x-heroicon-o-document-chart-bar class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Laporan Retur Pembelian" href="{{ route('manager.report.purchase.return') }}"
            :active="request()->routeIs('manager.report.purchase.return')" />

        <x-sidebar.sublink title="Laporan Retur Penjualan" href="{{ route('manager.report.sale.return') }}"
            :active="request()->routeIs('manager.report.sale.return')" />

        <x-sidebar.sublink title="Laporan Penjualan Sales" href="{{ route('manager.report.sales-by-salesman') }}"
            :active="request()->routeIs('manager.report.sales-by-salesman')" />

        <x-sidebar.sublink title="Laporan Riwayat Mutasi" href="{{ route('manager.report.mutation') }}"
            :active="request()->routeIs('manager.report.mutation')" />
    </x-sidebar.dropdown>

    {{-- @role('manager')
    <x-sidebar.link title="Pengguna" href="{{ route('manager.users.index') }}"
        :isActive="request()->routeIs('manager.users*')">
        <x-slot name="icon">
            <x-heroicon-o-user class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>
    @endrole --}}

    {{-- <x-sidebar.dropdown title="Kelola Keuangan" :active="request()->routeIs('manager.finance*')">
        <x-slot name="icon">
            <x-heroicon-o-document-currency-dollar class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Modal Utama" href="{{ route('manager.finance.modal.primaryModal') }}"
            :active="request()->routeIs('manager.finance.modal.primaryModal')" />
    </x-sidebar.dropdown>

    @role('manager|finance')
    <x-sidebar.dropdown title="Finance"
        :active="request()->routeIs('manager.modal*') || request()->routeIs('manager.asset*')">
        <x-slot name="icon">
            <x-heroicon-o-banknotes class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Kelola Modal" href="{{ route('manager.modal.index') }}"
            :active="request()->routeIs('manager.modal*')" />
        <x-sidebar.sublink title="Rincian Aset" href="{{ route('manager.asset.index') }}"
            :active="request()->routeIs('manager.asset*')" />
    </x-sidebar.dropdown>
    @endrole --}}

    @role('manager')
    {{-- Warehouse Start --}}

    {{-- <x-sidebar.link title="Distributor" href="{{ route('manager.distributors.index') }}"
        :isActive="request()->routeIs('manager.distributors*')">
        <x-slot name="icon">
            <x-heroicon-o-user-group class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link> --}}


    {{-- <div x-data="{ open: false }">
        <div class="flex items-center">
            <x-sidebar.linkToggle title="Gudang" href="{{ route('manager.warehouses.index') }}"
                :isActive="request()->routeIs('manager.warehouses*')" :collapsible="true">
                <x-slot name="icon">
                    <x-icons.warehouse class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
                </x-slot>
            </x-sidebar.linkToggle>
        </div>

        <div x-show="open && (isSidebarOpen || isSidebarHovered)" x-collapse
            class="relative px-0 pt-2 pb-0 ml-5 before:w-0 before:block before:absolute before:inset-y-0 before:left-0 before:border-l-2 before:border-l-gray-200 dark:before:border-l-gray-600 list-none">
            @foreach ($warehouses as $warehouse)
            <x-sidebar.sublink title="{{ $warehouse->name }}"
                href="{{ route('manager.warehouses.show', $warehouse->slug) }}" :active="request()->routeIs('manager.warehouses.show') &&
                            request()->route('warehouse') == $warehouse->slug" class="relative">
            </x-sidebar.sublink>
            @endforeach
        </div>
    </div> --}}
    {{-- Warehouse End --}}
    @endrole




    <x-sidebar.dropdown title="Lain Lain" :active="request()->routeIs('manager.other*')">
        <x-slot name="icon">
            <x-icons.list-bullet class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink title="Kategori" href="{{ route('manager.other.categories.index') }}"
            :active="request()->routeIs('manager.other.categories*')" />
    </x-sidebar.dropdown>

</x-perfect-scrollbar>