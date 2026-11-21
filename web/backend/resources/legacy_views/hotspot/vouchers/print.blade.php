<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Vouchers</title>
    <style>
        @page { size: A4; margin: 10mm; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: #fff; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 5mm; }
        .voucher { border: 1px dashed #ccc; padding: 5mm; position: relative; overflow: hidden; height: 40mm; }
        .header { display: flex; align-items: center; gap: 2mm; margin-bottom: 2mm; border-bottom: 1px solid #eee; padding-bottom: 1mm; }
        .logo { font-weight: bold; font-size: 14px; color: #333; }
        .profile { font-size: 11px; color: #666; font-weight: bold; text-transform: uppercase; }
        .code-box { background: #f4f4f4; border: 1px solid #ddd; padding: 2mm; text-align: center; margin-top: 2mm; }
        .code { font-family: 'Courier New', Courier, monospace; font-size: 20px; font-weight: bold; letter-spacing: 2px; }
        .footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 2mm; font-size: 9px; color: #888; }
        .price { font-weight: bold; font-size: 12px; color: #000; }
        @media print {
            .no-print { display: none; }
            .voucher { border-color: #000; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #333; color: #fff; padding: 10px; text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2ecc71; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">PRINT VOUCHERS</button>
        <p style="font-size: 12px; margin-top: 5px;">Tip: Set "Margins" to "None" in high print settings for best results.</p>
    </div>

    <div class="grid">
        @foreach ($vouchers as $voucher)
            <div class="voucher">
                <div class="header">
                    <div class="logo">{{ config('app.name') }} HOTSPOT</div>
                </div>
                <div class="profile">{{ $voucher->profile->display_name }} ({{ $voucher->profile->validity_hours }} Hours)</div>
                <div class="code-box">
                    <div class="code">{{ $voucher->code }}</div>
                </div>
                <div class="footer">
                    <div>Connect to: <strong>HOTSPOT-JABBAR</strong></div>
                    <div class="price">Rp {{ number_format($voucher->profile->price) }}</div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
