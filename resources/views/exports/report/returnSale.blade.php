<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SALE REPORT</title>
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
            <p class="title">LAPORAN RETUR PENJUALAN</p>
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
            <th>Tanggal</th>
            <th>Nomor Penjualan</th>
            <th>Nama Pembeli</th>
            <th>Nama Barang</th>
            <th>Jumlah Retur</th>
            <th>Satuan</th>
            <th>Alasan</th>
            <th>Harga Jual</th>
            <th>Total</th>
        </tr>

        @forelse ($returnSales as $singleSale)
            @foreach ($singleSale->items as $item)
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($singleSale->return_date)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $singleSale->sale->sale_number }}</td>
                    <td class="text-center">{{ $singleSale->buyer->contact }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="text-center">{{ $item->pivot->qty }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $singleSale->reason }}</td>
                    <td class="text-right">Rp {{ number_format($item->pivot->price_per_item, 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($singleSale->total_return, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="9" style="font-weight: bold; font-style: italic; text-align: center;">Tidak ada data
                    retur pembelian</td>
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
