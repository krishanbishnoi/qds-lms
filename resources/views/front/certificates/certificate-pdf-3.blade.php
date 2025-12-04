<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certificate</title>
<style>
    @page { size: A4 landscape; margin: 0; }
    body {
        margin: 0;
        padding: 0;
        font-family: "Helvetica Neue", Arial, sans-serif;
        width: 297mm;  /* A4 landscape width */
        height: 210mm; /* A4 landscape height */
    }

    .certificate-wrap {
        width: 100%;
        height: 100%;
        padding: 20mm;
        box-sizing: border-box;
        background: linear-gradient(135deg, #1f8ef1 0%, #6dd5ed 100%);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .certificate-card {
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        padding: 25px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 8px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        max-height: 60px;
    }

    .badge {
        font-weight: 700;
        color: #1f8ef1;
        font-size: 18px;
    }

    .headline {
        text-align: center;
        margin: 30px 0 10px 0;
    }

    .headline h1 {
        margin: 0;
        font-size: 32px;
        letter-spacing: 1px;
    }

    .headline p {
        margin: 6px 0 0;
        color: #666;
    }

    .name {
        text-align: center;
        font-size: 34px;
        font-weight: 700;
        margin-top: 20px;
        text-transform: capitalize;
    }

    .details {
        text-align: center;
        margin-top: 16px;
        color: #444;
        font-size: 16px;
        line-height: 1.4;
    }

    .footer {
        display: flex;
        justify-content: flex-start;
        margin-top: auto;
        padding-top: 30px;
    }

    .sig {
        text-align: center;
        margin-right: 50px;
    }

    .sig-line {
        border-top: 1px solid #ddd;
        width: 160px;
        margin: 0 auto 5px auto;
    }

    .small {
        font-size: 12px;
        color: #888;
    }
</style>
</head>
<body>
<div class="certificate-wrap">
    <div class="certificate-card">
        <div class="header">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logo ?? 'https://csilms.qdegrees.com/lms-img/creditsaison-logo.png')) }}" alt="logo" class="logo">
            <div class="badge">Certificate</div>
        </div>

        <div class="headline">
            <h1>Certificate of Achievement</h1>
            <p>Presented to</p>
        </div>

        <div class="name">{{ $name ?? Auth::user()->fullname }}</div>

        <div class="details">
            For successfully completing <strong>{{ $title ?? 'Soft Skills' }}</strong><br>
            Date: {{ $date ?? today()->format('d-M-Y') }}
        </div>

        <div class="footer">
            <div class="sig">
                <div class="sig-line"></div>
                <div class="small">Manager<br>Training & Development</div>
                <b style="font-weight: 500;font-size: 16px;color: #474645;">{{ $admin ?? 'Admin' }}</b>
            </div>
        </div>
    </div>
</div>
</body>
</html>
