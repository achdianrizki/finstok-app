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
            <p class="title">LAPORAN PEMBELIAN</p>
            <p class="period">
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
            </p>
            <p class="sevena">SEVENA</p>
        </div>
    </div>

    @php
        $totalQty = 0;
        $totalPurchasePrice = 0;
        $totalDiscount = 0;
    @endphp

    <table class="table">
        <tr>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Disc 1</th>
            <th>Disc 2</th>
            <th>Disc 3</th>
            <th>AD</th>
            <th>Harga Beli</th>
        </tr>

        @forelse ($purchases as $singlePurchase)
            @foreach ($singlePurchase->items as $item)
                @php
                    $quantity = $item->pivot->qty;
                    $price = $item->purchase_price;

                    $discount1Amount = ($item->pivot->discount1 / 100) * $price;
                    $discount2Amount = ($item->pivot->discount2 / 100) * $price;
                    $discount3Amount = ($item->pivot->discount3 / 100) * $price;
                    $totalItemDiscount = $discount1Amount + $discount2Amount + $discount3Amount;
                    $priceAfterDiscount = $price - $totalItemDiscount;

                    $subtotal = $quantity * $priceAfterDiscount;
                    $totalQty += $quantity;
                    $totalPurchasePrice += $subtotal;
                    $totalDiscount += $totalItemDiscount * $quantity;
                @endphp
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($singlePurchase->purchase_date)->format('d/m/Y') }}
                    </td>
                    <td>{{ $item->name }}</td>
                    <td class="text-center">{{ $item->pivot->qty }}</td>
                    <td>{{ $item->unit }}</td>
                    <td class="text-center">{{ number_format($item->pivot->discount1, 2, '.') ?? '0.00' }}</td>
                    <td class="text-center">{{ number_format($item->pivot->discount2, 2, '.') ?? '0.00' }}</td>
                    <td class="text-center">{{ number_format($item->pivot->discount3, 2, '.') ?? '0.00' }}</td>
                    <td class="text-center">{{ $item->pivot->ad }}</td>
                    <td class="text-right">Rp {{ number_format($item->purchase_price, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="9" style="font-weight: bold; font-style: italic; text-align: center;">Tidak ada data
                    pembelian</td>
            </tr>
        @endforelse
    </table>

    <table class="table">
        <tr>
            <td>
                <p style="font-weight: 500;">TOTAL PEMBELIAN</p>
            </td>
            <td style=" width: 260px;">{{ $totalQty }}</td>
            <td style="width: 100px;">
                <p>Rp {{ number_format($totalPurchasePrice, 2, ',', '.') }}</p>
            </td>
        </tr>
    </table>

</body>

</html>
