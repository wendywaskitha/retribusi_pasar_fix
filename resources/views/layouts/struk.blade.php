<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Retribusi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 10px;
        }
        .container {
            max-width: 300px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 10px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .items {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            margin-bottom: 10px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total {
            font-weight: bold;
            text-align: right;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">Struk Retribusi</div>
            <div class="subtitle">{{ $record->pasar->name }}</div>
        </div>

        <div class="info">
            <div class="info-item">
                <span>Nama Pedagang:</span>
                <span>{{ $record->pedagang->name }}</span>
            </div>
            <div class="info-item">
                <span>Tanggal:</span>
                <span>{{ $record->tanggal_bayar->format('d/m/Y') }}</span>
            </div>
            <div class="info-item">
                <span>Status:</span>
                <span>{{ ucfirst($record->status) }}</span>
            </div>
        </div>

        <div class="items">
            @foreach($record->items as $item)
                <div class="item">
                    <span>{{ $item->retribusi->name }}</span>
                    <span>Rp {{ number_format($item->biaya, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="total">
            <span>Total: Rp {{ number_format($record->total_biaya, 0, ',', '.') }}</span>
        </div>

        <div class="footer">
            <p>Dibuat oleh: {{ $record->user->name }}</p>
            <p>Terima kasih atas pembayaran Anda</p>
        </div>
    </div>
</body>
</html>
