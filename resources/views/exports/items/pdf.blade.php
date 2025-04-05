<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DATA BARANG</title>
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
            <p class="title">DATA BARANG</p>
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
            <th>Deskripsi</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Kategori</th>
            <th>Jumlah Stok</th>
        </tr>

        @forelse ($items as $item)
            <tr>
                <td>{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->unit }}</td>
                <td class="text-right">Rp {{ number_format($item->purchase_price, 2, ',', '.') }}</td>
                <td>{{ $item->category->name }}</td>
                <td>{{ $item->stock }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="font-weight: bold; font-style: italic; text-align: center;">
                    Tidak ada data barang
                </td>
            </tr>
        @endforelse
    </table>

</body>

</html>
