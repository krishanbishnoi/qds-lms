<style>
    @page {
        size: A4;
        margin: 0;
    }

    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: #f6f9fc;
        padding: 0;
        margin: 0;
    }

    .certificate-wrapper {
        width: 100%;
        padding: 40px;
        background: #fff;
        border: 12px solid #e4e4e4;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.05);
    }

    .certificate-border {
        border: 2px solid #d3d3d3;
        padding: 35px 50px;
        position: relative;
    }

    .corner-accent {
        position: absolute;
        width: 55px;
        height: 55px;
        border: 4px solid #00407E;
    }

    .top-left {
        top: -2px;
        left: -2px;
        border-right: none;
        border-bottom: none;
    }

    .top-right {
        top: -2px;
        right: -2px;
        border-left: none;
        border-bottom: none;
    }

    .bottom-left {
        bottom: -2px;
        left: -2px;
        border-right: none;
        border-top: none;
    }

    .bottom-right {
        bottom: -2px;
        right: -2px;
        border-left: none;
        border-top: none;
    }

    .logo {
        width: 150px;
        margin-bottom: 20px;
    }

    .title {
        font-size: 26px;
        font-weight: 700;
        color: #00407E;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
        margin-top: 10px;
    }

    .subtitle {
        font-size: 14px;
        color: #777;
        letter-spacing: 1px;
        text-align: center;
        margin-bottom: 35px;
    }

    .recipient {
        font-size: 38px;
        font-weight: 700;
        color: #222;
        text-align: center;
        margin: 10px 0;
        text-transform: capitalize;
    }

    .description {
        font-size: 15px;
        color: #444;
        width: 80%;
        margin: 0 auto 35px auto;
        text-align: center;
        line-height: 1.6;
    }

    .signature-section {
        margin-top: 40px;
        display: flex;
        justify-content: space-between;
        padding: 0 20px;
    }

    .sig-block {
        text-align: center;
    }

    .sig-line {
        width: 180px;
        height: 1px;
        background: #555;
        margin: 0 auto;
    }

    .sig-label {
        margin-top: 10px;
        color: #777;
        font-size: 13px;
    }

    .sig-name {
        margin-top: 3px;
        font-size: 17px;
        font-weight: 600;
        color: #444;
    }
</style>
@if ($isFromIndex == 1)
    <div class="certificate-wrapper">
        <div class="certificate-border">
            <div class="corner-accent top-left"></div>
            <div class="corner-accent top-right"></div>
            <div class="corner-accent bottom-left"></div>
            <div class="corner-accent bottom-right"></div>

            <div style="text-align: center;">
                <img src="{{ asset('lms-img/lmskey-logo.png') }}" class="logo">
            </div>

            <div class="title">Certificate of Completion</div>
            <div class="subtitle">Awarded to</div>

            <div class="recipient">{{ Auth::user()->fullname }}</div>

            <div class="description">
                This certificate acknowledges that <strong>{{ Auth::user()->fullname }}</strong> has successfully
                completed the training program <strong>Soft Skills</strong> on
                <strong>25-10-2025</strong>
            </div>

            <div class="signature-section">
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-label">Date</div>
                    <div class="sig-name">25-11-2025</div>
                </div>
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-label">Training & Development Manager</div>
                    <div class="sig-name">Admin</div>
                </div>

            </div>

        </div>
    </div>
@else
    <div class="certificate-wrapper">
        <div class="certificate-border">

            <!-- Corner accents -->
            <div class="corner-accent top-left"></div>
            <div class="corner-accent top-right"></div>
            <div class="corner-accent bottom-left"></div>
            <div class="corner-accent bottom-right"></div>

            <!-- Logo -->
            <div style="text-align: center;">
                <img src="{{ asset('lms-img/lmskey-logo.png') }}" class="logo">
            </div>

            <!-- Title -->
            <div class="title">Certificate of Completion</div>
            <div class="subtitle">Awarded to</div>

            <!-- Recipient Name -->
            <div class="recipient">{{ Auth::user()->fullname }}</div>

            <!-- Description -->
            <div class="description">
                This certificate acknowledges that <strong>{{ Auth::user()->fullname }}</strong> has successfully
                completed the training program <strong>{{ $trainingData->title }}</strong> on
                <strong>{{ today()->format('d-M-Y') }}</strong>
            </div>

            <!-- Signature section -->
            <div class="signature-section">

                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-label">Date</div>
                    <div class="sig-name">{{ today()->format('d-M-Y') }}</div>
                </div>

                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-label">Training & Development Manager</div>
                    <div class="sig-name">{{ $admin }}</div>
                </div>

            </div>

        </div>
    </div>
@endif
