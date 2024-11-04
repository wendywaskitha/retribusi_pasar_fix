<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laporan Retribusi</title>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <h1>Laporan Retribusi</h1>

    @if(auth()->user()->hasRole('kolektor'))
        <p>Kolektor: {{ $userName }}</p>
        <p>Pasar: {{ $pasarName }}</p>
    @endif

    <!-- Date range if filters are applied -->
    @if(isset($filters['date_range']))
        <p>Periode: {{ $filters['date_range']['from'] ?? '' }} - {{ $filters['date_range']['until'] ?? '' }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pedagang</th>
                <th>Pasar</th>
                <th>Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_bayar->format('d/m/Y') }}</td>
                    <td>{{ $item->pedagang->name }}</td>
                    <td>{{ $item->pasar->name }}</td>
                    <td>Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right"><strong>Total:</strong></td>
                <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
