<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Label - {{ $item->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f3f4f6;
        }
        .card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            border: 2px solid #e5e7eb;
        }
        .title {
            font-size: 10px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .name {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 10px;
        }
        .code-img {
            margin: 0 auto 10px auto;
            display: block;
        }
        .code-text {
            font-family: monospace;
            font-size: 13px;
            font-weight: bold;
            background-color: #f3f4f6;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
        }
        .no-print {
            margin-top: 15px;
            text-align: center;
        }
        .btn-print {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        @media print {
            body { background-color: white; }
            .card { box-shadow: none; border: 1px solid #000; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div>
        <div class="card">
            <div class="title">INVENTARIS SEKOLAH</div>
            <div class="name">{{ $item->name }}</div>
            
            @if($type === 'barcode')
                <img class="code-img" style="width: {{ $dimension * 1.5 }}px; height: {{ $dimension * 0.6 }}px;" 
                     src="https://quickchart.io/barcode?type=code128&text={{ urlencode($item->code) }}&includeText=false" 
                     alt="Barcode {{ $item->code }}">
            @else
                <img class="code-img" style="width: {{ $dimension }}px; height: {{ $dimension }}px;" 
                     src="https://quickchart.io/qr?text={{ urlencode($item->code) }}&size={{ $dimension }}" 
                     alt="QR Code {{ $item->code }}">
            @endif

            <div class="code-text">{{ $item->code }}</div>
        </div>

        <div class="no-print">
            <button onclick="window.print()" class="btn-print">🖨️ Cetak Sekarang</button>
        </div>
    </div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>