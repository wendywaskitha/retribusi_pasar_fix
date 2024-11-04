<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retribusi Realization Report</title>
    <style>
        /* Add your CSS styles here */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Retribusi Realization Report</h1>
    <table>
        <thead>
            <tr>
                <th>Pasar</th>
                <th>Pedagang</th>
                <th>Tanggal Pembayaran</th>
                <th>Jumlah Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td>{{ $record->pedagang->pasar->name ?? 'N/A' }}</td>
                    <td>{{ $record->pedagang->name ?? 'N/A' }}</td>
                    <td>{{ $record->tanggal_bayar ? $record->tanggal_bayar->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $record->total_biaya ? number_format($record->total_biaya, 2, ',', '.') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
