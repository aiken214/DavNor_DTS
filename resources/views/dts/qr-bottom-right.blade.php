<!DOCTYPE html>
<html>
<head>
    <title>DTS QR Code</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body {
            margin: 0.4cm 0.4cm;
            font-family: "Century Gothic", sans-serif;
            position: relative;
            height: 100vh;
        }
        .bottom-right {
            position: absolute;
            bottom: 2cm; /* Move up by 2cm */
            right: 2cm;  /* Add margin from right */
            text-align: center;
        }
        .qr-code {
            margin-top: 3rem;
        }
        .tracking-code {
            font-size: 0.8rem;
        }
        .dts {
            font-size: 0.55rem;
            /* padding-bottom: 0.2rem; */
        }
    </style>
</head>
<body>
    <div class="bottom-right">
        <div class="qr-code">
            <div class="dts">DTS</div>
            <img src="data:image/png;base64,{{ base64_encode(QrCode::size(60)->generate($document->tracking_code)) }}">
        </div>
        <div class="tracking-code">{{ $document->tracking_code }}</div>
    </div>
</body>
</html>