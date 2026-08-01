<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h2 { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <p>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No. Registrasi</th>
                <th>Nama</th>
                <th>Desa</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Dikunjungi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($submissions as $s)
                <tr>
                    <td>{{ $s->registration_number }}</td>
                    <td>{{ $s->full_name }}</td>
                    <td>{{ $s->village->name }}</td>
                    <td>{{ $s->product_name }}</td>
                    <td>{{ $s->category ?? '-' }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $s->status)) }}</td>
                    <td>{{ $s->visited ? 'Ya' : 'Tidak' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
