<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PURCHASE REPORT</title>
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
            font-size: 10px;
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
            <p class="title">LAPORAN MUTASI GUDANG</p>
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
            <th class="text-center">No</th>
            <th>Tanggal Mutasi</th>
            <th>Nama Barang</th>
            <th>Dari Gudang</th>
            <th>Ke Gudang</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
        </tr>

        @forelse ($mutations as $mutation)
            <tr>
                <td>{{ $loop->iteration  }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($mutation->mutated_at)->format('d/m/Y') }}</td>
                <td>{{ $mutation->item_name }}</td>
                <td>{{ $mutation->from_warehouse_name }}</td>
                <td>{{ $mutation->to_warehouse_name }}</td>
                <td class="text-center">{{ $mutation->qty }}</td>
                <td>{{ $mutation->note }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="font-weight: bold; font-style: italic; text-align: center;">Tidak ada data mutasi barang</td>
            </tr>
        @endforelse
    </table>

    {{-- <table class="table">
        <tr>
            <td>
                <p style="font-weight: 500;">TOTAL PEMBELIAN</p>
            </td>
            <td style=" width: 240px;">{{ $totalQty }}</td>
            <td style="width: 100px;">
                <p>Rp {{ number_format($totalPurchasePrice - $totalDiscount, 2, ',', '.') }}</p>
            </td>
        </tr>
    </table> --}}

</body>

</html>
