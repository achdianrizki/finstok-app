<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DATA SUPPLIER / PEMASOK BARANG</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-right: 15px;
        }

        .title-container {
            flex: 1;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-top: -70px;
        }

        .title {
            margin: 5px 0;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .period {
            font-size: 12px;
            font-weight: 500;
            margin-top: -5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 8px;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .table td {
            vertical-align: middle;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 50px;
        }

        .sevena {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: -20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/image/logo.png'))) }}"
            class="logo">

        <div class="title-container">
            <p class="title">DATA SUPPLIER / PEMASOK BARANG</p>
            {{-- <p class="period">
                Periode:
                @if ($period === 'day')
                    {{ now()->toFormattedDateString() }}
                @elseif ($period === 'month')
                    {{ now()->format('F Y') }}
                @elseif ($period === 'custom' && request()->has('start_date') && request()->has('end_date'))
                    {{ \Carbon\Carbon::parse(request()->start_date)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse(request()->end_date)->format('d M Y') }}
                @else
                    Semua Data
                @endif
            </p> --}}
            <p class="sevena">SEVENA</p>
        </div>
    </div>

    <table class="table">
        <tr>
            <th style="width: 10px;">Kode</th>
            <th>Nama</th>
            <th>No Telepon</th>
            <th>No Fax</th>
            <th>NPWP</th>
            <th>Alamat</th>
            <th>Kota</th>
            <th>Provinsi</th>
            <th>Status</th>
        </tr>

        @forelse ($suppliers as $supplier)
            <tr>
                <td>{{ $supplier->supplier_code }}</td>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->phone }}</td>
                <td>{{ $supplier->fax_nomor ?? '-' }}</td>
                <td>{{ $supplier->npwp ?? '-' }}</td>
                <td>{{ $supplier->address }}</td>
                <td>{{ $supplier->city }}</td>
                <td>{{ $supplier->province }}</td>
                <td>{{ $supplier->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="font-weight: bold; font-style: italic; text-align: center;">
                    Tidak ada data supplier
                </td>
            </tr>
        @endforelse
    </table>

</body>

</html>
