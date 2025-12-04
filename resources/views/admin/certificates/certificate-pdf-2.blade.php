<style>
    @page {
        size: A4;
        margin: 30mm 20mm;
    }

    body {
        font-family: "Georgia", serif;
        color: #222;
        background: #fff;
    }

    .certificate {
        border: 12px solid #d7c7a8;
        padding: 40px;
        text-align: center;
        height: 100%;
        box-sizing: border-box;
    }

    .logo {
        max-width: 140px;
        margin-bottom: 10px;
    }

    .title {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-top: 10px;
    }

    .sub {
        font-size: 14px;
        color: #555;
        margin-bottom: 30px;
    }

    .recipient {
        font-size: 36px;
        font-weight: 700;
        margin: 20px 0;
        text-transform: capitalize;
    }

    .course {
        font-size: 18px;
        color: #333;
        margin-bottom: 24px;
    }

    .meta {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        align-items: center;
    }

    .meta .left,
    .meta .right {
        width: 45%;
    }

    .signature {
        text-align: center;
    }

    .sig-line {
        border-top: 1px solid #aaa;
        width: 70%;
        margin: 0 auto;
        padding-top: 8px;
        color: #333;
    }

    .small {
        font-size: 11px;
        color: #666;
        margin-top: 8px;
    }
</style>
@if ($isFromIndex == 1)
    <div class="certificate">

        <img src="{{ asset('lms-img/lmskey-logo.png') }}" alt="logo" class="logo">
        <div class="title">Certificate of Completion</div>
        <div class="sub">This is to certify that</div>

        <div class="recipient">{{ Auth::user()->fullname }}</div>

        <div class="course">has successfully completed the course<br><strong>Soft Skills</strong></div>

        <div class="meta">
            <div class="left">
                <div class="signature">
                    <div class="sig-line">Date</div>
                    <div class="small">25-10-2025</div>
                </div>
            </div>
            <div class="right">
                <div class="signature">
                    <div class="sig-line">Manager</div>
                    <div class="small">Training & Development</div>
                    <b style="font-weight: 500;font-size: 16px;color: #474645;">Admin</b></span>

                </div>
            </div>
        </div>
    </div>
@else
    <div class="certificate">

        <img src="{{ asset('lms-img/lmskey-logo.png') }}" alt="logo" class="logo">
        <div class="title">Certificate of Completion</div>
        <div class="sub">This is to certify that</div>

        <div class="recipient">{{ Auth::user()->fullname }}</div>

        <div class="course">has successfully completed the course<br><strong>{{ $trainingData->title }}</strong></div>

        <div class="meta">
            <div class="left">
                <div class="signature">
                    <div class="sig-line">Date</div>
                    <div class="small">{{ today()->format('d-M-Y') }}</div>
                </div>
            </div>
            <div class="right">
                <div class="signature">
                    <div class="sig-line">Manager</div>
                    <div class="small">Training & Development</div>
                    <b style="font-weight: 500;font-size: 16px;color: #474645;">{{ $admin }}</b></span>

                </div>
            </div>
        </div>
    </div>
@endif
