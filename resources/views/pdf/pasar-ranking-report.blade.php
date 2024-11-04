<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Pasar Ranking Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
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
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Pasar Ranking Report</h2>
        <h3>{{ $month }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pasar</th>
                <th>Jumlah Pedagang</th>
                <th>Total Retribusi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pasars as $index => $pasar)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pasar['name'] }}</td>
                    <td>{{ $pasar['total_pedagang'] }}</td>
                    <td>Rp {{ number_format($pasar['total_retribusi'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="3">Total</td>
                <td>Rp {{ number_format($pasars->sum('total_retribusi'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 20px">
        <p>Generated on: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
