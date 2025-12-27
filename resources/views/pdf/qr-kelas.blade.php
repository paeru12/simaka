<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: A5;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 10mm;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 2mm;
        }

        .subtitle {
            font-size: 12px;
            margin-bottom: 5mm;
        }

        .qr img {
            width: 110mm;
            height: auto;
        }

        .footer {
            margin-top: 6mm;
            font-size: 12px;
            font-weight: bold;
        }

        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="no-break">
        <div class="title">{{ strtoupper($nama) }}</div>
        <div class="subtitle">Scan untuk absensi</div>

        <div class="qr">
            <img src="{{ public_path($qrPath) }}">
        </div>

        <div class="footer">
            <img src="{{ public_path($logo) }}" width="60">
            <h3>{{ $appName }}</h3>
        </div>
    </div>
</body>
</html>
