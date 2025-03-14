<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SALE ONE ON ONE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: 400;
            text-align: center;
        }

        .table-head {
            width: 280px;
        }

        .table-head td {
            font-size: 14px;
        }

        .table th {
            font-size: 10px;
        }

        .table td {
            font-size: 10px;
        }

        .table .td-none td {
            font-size: 10px;
            border-top: none;
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-bottom: none;
            padding: 3px;
        }

        .signature {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            font-size: 12px;
        }

        .text {
            margin-top: 0px;
            margin-bottom: 50px;
        }

        .name {
            margin-top: -5px;
            margin-bottom: -5px;
        }

        .signature span {
            border-bottom: 1.5px solid black;
            display: inline-block;
            width: 100px;
        }

        .total-section {
            font-size: 12px;
            margin-bottom: -15px;
        }

        .total-section p {
            margin-top: -10px;
        }

        .bx-none td {
            border-right: none;
        }

        .information p {
            margin-bottom: -7px;
        }

        .border-none {
            border-top: none;
            border-bottom: none;
            border-left: none;
            border-right: none;
        }
    </style>
</head>

<body>

    <table class="table" style="margin-top: -20px">
        <tr>
            <td style="width: 390px; border-top: none; border-bottom: none; border-left: none; border-right: none;">
                <table class="table information" style="width: 400px">
                    <tr>
                        <td colspan="2">
                            <p style="font-size: 10px; padding: 0; margin: 0;">{{ $purchase->supplier->contact }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 15px; border-right: none;">
                            <p style="margin-top: -5px">Alamat</p>
                            <p>NPWP</p>
                            <p>Bank</p>
                            <p>Telp</p>
                            <p>Fax</p>
                        </td>
                        <td style="border-left: none;">
                            <p style="margin-top: -5px">: {{ $purchase->supplier->address }}</p>
                            <p>: </p>
                            <p>: </p>
                            <p>: {{ $purchase->supplier->phone }}</p>
                            <p>: </p>
                        </td>
                    </tr>
                </table>
            </td>
            <td rowspan="2" style="border-top: none; border-bottom: none; border-left: none; border-right: none;">
                <table class="table" style="width: 280px; margin-top: -2px;">
                    <tr>
                        <td style="font-size: 14px;" colspan="2">Faktur Pembelian</td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <p style=" margin-top: -5px;">Tanggal</p>
                            <p style="text-align: right;">{{ $purchase->purchase_date }}</p>
                        </td>
                        <td style="width: 50%;">
                            <p style=" margin-top: -5px;">Nomor</p>
                            <p style="text-align: right;">{{ $purchase->purchase_number }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <p style=" margin-top: -5px;">Jatuh Tempo</p>
                            <p style="text-align: right; margin-bottom: 5px;">-</p>
                        </td>
                        <td style="width: 50%;">
                            <p style="margin-top: -5px;">No. PIT</p>
                            <p style="text-align: right;">-</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <p style=" margin-top: -5px;">Salesman</p>
                            {{-- <p style="text-align: right;">{{ $purchase->salesman->name ? $purchase->salesman->name : '' }}</p> --}}
                        </td>
                        <td style="width: 50%;">
                            <p style=" margin-top: -5px;">Kode Area</p>
                            <p style="text-align: right;">-</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="border-top: none; border-bottom: none; border-left: none; border-right: none;">
                <table class="table information" style="width: 400px; margin-top: -25px;">
                    <tr>
                        <td>Kepada, </td>
                    </tr>
                    <tr>
                        <td>SAVENA KOSMETIK</td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin-top: -5px;">jl. Katapang</p>
                            <p>Hari ??</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>

    <table class="table" style="margin-top: 5px">
        <tr>
            <th style="width: 10px;">No</th>
            <th style="width: 200px;">Nama Barang</th>
            <th style="width: 20px;">Qty</th>
            <th style="width: 20px;">Satuan</th>
            <th style="width: 70px;">Harga</th>
            <th style="width: 30px;">Disc 1</th>
            <th style="width: 30px;">Disc 2</th>
            <th style="width: 30px;">Disc 3</th>
            <th style="width: 30px;">Ad</th>
            <th>Total</th>
        </tr>
        @foreach ($items as $item)
            <tr class="td-none">
                <td style="text-align: center">{{ $loop->iteration }}</td>
                <td style="text-align: left">{{ $item['name'] }}</td>
                <td style="text-align: right">{{ $item['qty'] }}</td>
                <td style="text-align: left">{{ $item['unit'] }}</td>
                <td style="text-align: right">{{ number_format($item['purchase_price'], 2, ',', '.') }}</td>
                <td style="text-align: right">{{ $item['discount1'] ?: 0 }}%</td>
                <td style="text-align: right">{{ $item['discount2'] ?: 0 }}%</td>
                <td style="text-align: right">{{ $item['discount3'] ?: 0 }}%</td>
                <td style="text-align: right">{{ $item['ad'] ?: 0 }}</td>
                <td style="text-align: right">{{ number_format($item['price_per_item'], 2, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="text-align: center; font-size: 12px;" colspan="2">Total</td>
            <td style="text-align: right; padding: 3px;"> {{ $items->sum('qty') }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <table class="table" style="margin-top: -1px">
        <tr>
            <td style="width: 160px; border-right: none;">
                <div class="signature">
                    <p class="text">Diterima oleh,</p>
                    <span></span>
                    <p class="name">(..................................)</p>
                </div>
            </td>
            <td style="width:160px; border-left: none;">
                <div class="signature">
                    <p class="text">Diterima oleh,</p>
                    <span></span>
                    <p class="name">(..................................)</p>
                </div>
            </td>
            <td style="border-right: none">
                <div class="total-section">
                    <p style="margin-top: -2px;">Total</p>
                    <p>Discount 1</p>
                    <p>Discount 2</p>
                    <p>Discount 3</p>
                    <p>PPN</p>
                    <p>Jumlah yang harus dibayar</p>
                </div>
            </td>
            <td style="width: 170px; border-left: none">
                <div class="total-section">
                    <p style="text-align: right; margin-top: -2px;">{{ number_format($purchase->sub_total, 2, ',', '.') }}</p>
                    <p style="text-align: right;">{{ number_format($purchase->total_discount1, 2, ',', '.') }}</p>
                    <p style="text-align: right;">{{ number_format($purchase->total_discount2, 2, ',', '.') }}</p>
                    <p style="text-align: right;">{{ number_format($purchase->total_discount3, 2, ',', '.') }}</p>
                    <p style="text-align: right;">{{ number_format($purchase->tax, 2, ',', '.') }}</p>
                    <p style="text-align: right;">{{ number_format($purchase->total_price, 2, ',', '.') }}</p>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
