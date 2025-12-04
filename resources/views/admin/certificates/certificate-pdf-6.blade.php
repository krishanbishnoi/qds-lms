<style>
    @page {
        size: A4;
        margin: 20mm;
    }

    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background: #f6f6f6;
    }

    .certificate-wrapper {
        background: #fff;
        border: 12px solid #00407E22;
        padding: 40px 50px;
        position: relative;
        overflow: hidden;
    }

    /* Light luxury watermark */
    .certificate-wrapper::before {
        content: "";
        position: absolute;
        inset: 0;
        background: url('{{ asset('front/img/backgroundimage.png') }}') center/60% no-repeat;
        opacity: 0.05;
        z-index: 0;
    }

    /* Gold corner accents */
    .certificate-wrapper::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        border-top: 40px solid #ed1c24;
        border-right: 40px solid transparent;
        opacity: .7;
    }

    .certificate-inner {
        position: relative;
        z-index: 2;
    }

    .top-logo {
        text-align: right;
        margin-bottom: 20px;
    }

    .top-logo img {
        width: 120px;
        opacity: 0.9;
    }

    .heading {
        text-align: center;
        font-size: 30px;
        font-weight: 800;
        color: #333;
        letter-spacing: 2px;
        margin-top: 5px;
    }

    .subtitle {
        text-align: center;
        font-size: 14px;
        color: #888;
        margin-top: -8px;
    }

    .name-box {
        text-align: center;
        margin-top: 40px;
    }

    .name {
        font-size: 42px;
        font-weight: 800;
        color: #ed1c24;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .description {
        width: 70%;
        margin: auto;
        font-size: 14px;
        color: #444;
        line-height: 1.6;
        text-align: center;
    }

    .footer {
        margin-top: 60px;
        display: flex;
        justify-content: space-between;
    }

    .footer .block {
        text-align: center;
        width: 45%;
    }

    .footer .label {
        font-size: 12px;
        color: #777;
    }

    .footer .value {
        font-size: 18px;
        font-weight: 700;
        margin-top: 6px;
        color: #444;
    }
</style>

@if ($isFromIndex == 1)
    <div class="certificate-wrapper">
        <div class="certificate-inner">

            <div class="top-logo">
                <img src="{{ asset('lms-img/creditsaison-logo.svg') }}">
            </div>

            <div class="heading">CERTIFICATE OF COMPLETION</div>
            <div class="subtitle">Recognizing outstanding achievement</div>

            <div class="name-box">
                <div class="name">{{ Auth::user()->fullname }}</div>

                <div class="description">
                    This certificate is proudly awarded for successfully completing the training program
                    <strong>Soft Skills</strong> on
                    <strong>25-11-2025</strong>,
                    delivered via the QDegrees LMS platform.
                </div>
            </div>

            <div class="footer">
                <div class="block">
                    <div class="label">Date</div>
                    <div class="value">25-11-2025</div>
                </div>

                <div class="block">
                    <div class="label">Training & Development Manager</div>
                    <div class="value">Admin</div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="certificate-wrapper">
        <div class="certificate-inner">

            <div class="top-logo">
                <img src="{{ asset('lms-img/creditsaison-logo.svg') }}">
            </div>

            <div class="heading">CERTIFICATE OF COMPLETION</div>
            <div class="subtitle">Recognizing outstanding achievement</div>

            <div class="name-box">
                <div class="name">{{ Auth::user()->fullname }}</div>

                <div class="description">
                    This certificate is proudly awarded for successfully completing the training program
                    <strong>{{ $trainingData->title }}</strong> on
                    <strong>{{ today()->format('d-M-Y') }}</strong>,
                    delivered via the QDegrees LMS platform.
                </div>
            </div>

            <div class="footer">
                <div class="block">
                    <div class="label">Date</div>
                    <div class="value">{{ today()->format('d-M-Y') }}</div>
                </div>

                <div class="block">
                    <div class="label">Training & Development Manager</div>
                    <div class="value">{{ $admin }}</div>
                </div>
            </div>
        </div>
    </div>
@endif
