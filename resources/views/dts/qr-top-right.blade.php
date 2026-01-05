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
        .top-right {
            position: absolute;
            top: 0.4cm;
            right: 1.2cm;
            text-align: center;
        }
        .qr-code {
            margin-top: 3rem;
        }
        .tracking-code {
            /* margin-top: 0.4rem; */
            font-size: 0.8rem;
        }
    .dts{
        font-size: 0.55rem;
        /* padding-bottom: .4rem; */
    }
    </style>
</head>
<body>
    <div class="top-right">
        
        <div class="qr-code">
            <div class="dts"> DTS</div>
            <img src="data:image/png;base64,{{ base64_encode(QrCode::size(60)->generate($document->tracking_code)) }}">
        </div>
        <div class="tracking-code">
            {{ $document->tracking_code }}
        </div>
    </div>
</body>
</html>