<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retribusi Receipt</title>
    <style>
        @page {
            size: 58mm 48mm;
            margin: 0;
        }

        @media screen {
            .print-button {
                display: none;
            }
        }

        @media print {
            .print-button {
                display: none;
            }
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.2;
            width: 56mm;
            margin: 0 auto;
            padding: 1mm;
        }
        h1 {
            font-size: 10pt;
            text-align: center;
            margin: 2mm 0;
        }
        h2 {
            font-size: 9pt;
            margin: 1mm 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2mm;
        }
        th, td {
            padding: 0.5mm 1mm;
            text-align: left;
            border-bottom: 0.1mm dotted #000;
        }
        .total {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 7pt;
            margin-top: 2mm;
        }
    </style>
</head>
<body>
    <h1>Bukti Pembayaran Retribusi</h1>

    <table>
        <tr>
            <th>Pasar</th>
            <td>{{ $record->pedagang->pasar->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Pedagang</th>
            <td>{{ $record->pedagang->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ $record->tanggal_bayar ? \Carbon\Carbon::parse($record->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
        </tr>
    </table>

    <h2>Rincian Retribusi</h2>
    <table>
        @foreach($record->retribusi_pembayaran_items as $item)
            <tr>
                <td>{{ $item->retribusi->name ?? '-' }}</td>
                <td align="right">Rp {{ number_format($item->biaya, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="total">
            <td>Total</td>
            <td align="right">Rp {{ number_format($record->total_biaya, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        Terima kasih atas pembayaran Anda<br>
        {{ now()->format('d/m/Y H:i:s') }}<br>
        Petugas: {{ $record->user->name }}
    </div>

    <button class="print-button" onclick="window.print()">Print</button>

    <script>
        // This script will only run in the preview modal
        if (window.self !== window.top) {
            document.querySelector('.print-button').style.display = 'block';
        }
    </script>
</body>
</html>
