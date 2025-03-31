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
            <p class="title">LAPORAN PENJUALAN SALES</p>
            <p class="title">{{ $salesman->name ?? 'SEMUA SALES' }}</p>
            <p class="sevena">SEVENA</p>
        </div>
    </div>

    @php
        $totalQtySold = 0;
        $totalSalePrice = 0;
        $totalDiscount = 0;
    @endphp

    <table class="table">
        <tr>
            <th>Tanggal Penjualan</th>
            <th>Nomor Penjualan</th>
            <th>Qty</th>
            <th>Sub Total</th>
            <th>Disc 1</th>
            <th>Disc 2</th>
            <th>Disc 3</th>
            <th>Total Disc</th>
            {{-- <th>Status</th> --}}
            <th>Pajak</th>
            <th>Total Price</th>
        </tr>

        @forelse ($sales as $singleSale)
            <tr>
                <td class="text-center">{{ \Carbon\Carbon::parse($singleSale->sale_date)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $singleSale->sale_number }}</td>
                <td class="text-center">{{ $singleSale->qty_sold }}</td>
                <td>Rp {{ number_format($singleSale->sub_total, 2, '.') ?? '0.00' }}</td>
                <td class="text-center">Rp {{ number_format($singleSale->discount1_value, 2, '.') ?? '0.00' }}</td>
                <td class="text-center">Rp {{ number_format($singleSale->discount2_value, 2, '.') ?? '0.00' }}</td>
                <td class="text-center">Rp {{ number_format($singleSale->discount3_value, 2, '.') ?? '0.00' }}</td>
                <td class="text-center">Rp {{ number_format($singleSale->total_discount, 2, '.') ?? '0.00' }}</td>
                {{-- <td class="text-right">{{$singleSale->status }}</td> --}}
                <td class="text-right">Rp {{ number_format($singleSale->tax, 2, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($singleSale->total_price, 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="font-weight: bold; font-style: italic; text-align: center;">Tidak ada data
                    penjualan</td>
            </tr>
        @endforelse
    </table>

    {{-- <table class="table">
        <tr>
            <td>
                <p style="font-weight: 500;">TOTAL PENJUALAN</p>
            </td>
            <td style=" width: 240px;">{{ $totalQtySold }}</td>
            <td style="width: 100px;">
                <p>Rp {{ number_format($totalSalePrice - $totalDiscount, 2, ',', '.') }}</p>
            </td>
        </tr>
    </table> --}}

</body>

</html>
