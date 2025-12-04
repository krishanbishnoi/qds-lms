<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certificate</title>
<style>
    @page {
        size: A4;
        margin: 0;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: #f6f9fc;
    }

    .certificate-wrapper {
        width: 210mm;
        height: 297mm;
        padding: 0;
        box-sizing: border-box;
        background: #fff;
    }

    .certificate-border {
        width: 100%;
        height: 100%;
        padding: 15mm;
        border: 2mm solid #d3d3d3;
        box-sizing: border-box;
        position: relative;
        display: block;
    }

    /* Corner accents */
    .corner-accent {
        position: absolute;
        width: 40px;
        height: 40px;
        border: 4px solid #00407E;
    }
    .top-left { top: 0; left: 0; border-right: none; border-bottom: none; }
    .top-right { top: 0; right: 0; border-left: none; border-bottom: none; }
    .bottom-left { bottom: 0; left: 0; border-right: none; border-top: none; }
    .bottom-right { bottom: 0; right: 0; border-left: none; border-top: none; }

    .logo {
        display: block;
        max-width: 150px;
        margin: 20px auto;
    }

    .title {
        font-size: 32px;
        font-weight: 700;
        color: #00407E;
        text-align: center;
        text-transform: uppercase;
        margin: 10px 0;
    }

    .subtitle {
        font-size: 16px;
        color: #777;
        text-align: center;
        margin: 10px 0 20px 0;
    }

    .recipient {
        font-size: 36px;
        font-weight: 700;
        color: #ed1c24;
        text-align: center;
        margin: 15px 0;
        text-transform: capitalize;
    }

    .description {
        font-size: 16px;
        color: #444;
        text-align: center;
        width: 85%;
        margin: 0 auto;
        line-height: 1.6;
    }

    .signature-section {
        display: flex;
        justify-content: space-between;
        margin-top: 50px;
        padding: 0 20px;
    }

    .sig-block {
        text-align: center;
    }

    .sig-line {
        width: 160px;
        height: 1px;
        background: #555;
        margin: 0 auto;
    }

    .sig-label {
        margin-top: 8px;
        font-size: 13px;
        color: #777;
    }

    .sig-name {
        margin-top: 3px;
        font-size: 16px;
        font-weight: 600;
        color: #444;
    }
</style>
</head>
<body>
<div class="certificate-wrapper">
    <div class="certificate-border">
        <!-- Corners -->
        <div class="corner-accent top-left"></div>
        <div class="corner-accent top-right"></div>
        <div class="corner-accent bottom-left"></div>
        <div class="corner-accent bottom-right"></div>

        <!-- Logo -->
        <img src="{{ asset('lms-img/lmskey-logo.png') }}" class="logo">

        <!-- Title -->
        <div class="title">Certificate of Completion</div>
        <div class="subtitle">Awarded to</div>

        <!-- Recipient -->
        <div class="recipient">{{ $name ?? Auth::user()->fullname }}</div>

        <!-- Description -->
        <div class="description">
            This certificate acknowledges that <strong>{{ $name ?? Auth::user()->fullname }}</strong> has successfully completed the training program <strong>{{ $trainingData->title ?? 'Soft Skills' }}</strong> on <strong>{{ $date ?? today()->format('d-M-Y') }}</strong>.
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Date</div>
                <div class="sig-name">{{ $date ?? today()->format('d-M-Y') }}</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Training & Development Manager</div>
                <div class="sig-name">{{ $admin ?? 'Admin' }}</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
