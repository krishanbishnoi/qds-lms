<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            font-family: "Georgia", serif;
            box-sizing: border-box;
        }

        table.certificate-wrapper {
            width: 100%;
            border: 10px solid #d7c7a8;
            background-image: url('{{ $background_img ?? "" }}');
            background-size: cover;
            background-position: center;
            border-collapse: collapse;
            text-align: center;
            page-break-inside: avoid;
        }

        table.certificate-wrapper td {
            padding: 20mm 15mm;
        }

        .logo {
            max-height: 60px;
            margin-bottom: 15px;
        }

        .title {
            font-size: 32px;
            font-weight: bold;
            color: #00407E;
            margin-bottom: 10px;
        }

        .sub {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .recipient {
            font-size: 36px;
            font-weight: bold;
            color: #ed1c24;
            margin-bottom: 20px;
        }

        .course {
            font-size: 22px;
            margin-bottom: 30px;
        }

        .signature-wrapper {
            margin-top: 30px;
            width: 100%;
            display: flex;
            justify-content: space-around;
        }

        .signature {
            width: 40%;
            text-align: center;
        }

        .sig-line {
            border-top: 1px solid #aaa;
            width: 70%;
            margin: 0 auto 5px auto;
        }

        .sig-text {
            font-size: 14px;
            color: #333;
        }

        tr, td, table {
            page-break-inside: avoid !important;
        }
    </style>

</head>
<body>

<table class="certificate-wrapper">
    <tr>
        <td>

            <!-- LOGO -->
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logo ?? 'https://csilms.qdegrees.com/lms-img/creditsaison-logo.png')) }}" class="logo">

            <!-- TITLE -->
            <div class="title">Certificate of Completion</div>

            <!-- SUB TEXT -->
            <div class="sub">This is to certify that</div>

            <!-- NAME -->
            <div class="recipient">{{ $name ?? Auth::user()->fullname }}</div>

            <!-- COURSE -->
            <div class="course">
                has successfully completed the course<br>
                <strong>{{ $title ?? 'Soft Skills' }}</strong>
            </div>

            <!-- SIGNATURES -->
            <div class="signature-wrapper">
                <div class="signature">
                    <div class="sig-line"></div>
                    <div class="sig-text">Date: {{ $date ?? today()->format('d-M-Y') }}</div>
                </div>

                <div class="signature">
                    <div class="sig-line"></div>
                    <div class="sig-text">Manager<br><strong>{{ $admin ?? 'Admin' }}</strong></div>
                </div>
            </div>

        </td>
    </tr>
</table>

</body>
</html>

