<style>
    @page {
        size: A4;
        margin: 18mm;
    }

    body {
        font-family: "Segoe UI", Tahoma, sans-serif;
        background: #f6f6f6;
    }

    .certificate-wrapper {
        background: #fff;
        border: 10px solid #e4e4e4;
        padding: 28px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    /* Decorative inner border */
    .certificate-inner {
        border: 3px solid #d0d0d0;
        padding: 50px 40px;
    }

    .heading-main {
        text-align: center;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: 1px;
        color: #2c2c2c;
        text-transform: uppercase;
    }

    .sub-text {
        text-align: center;
        font-size: 14px;
        margin-top: 6px;
        color: #666;
        letter-spacing: .5px;
    }

    .username {
        text-align: center;
        font-size: 34px;
        font-weight: 800;
        color: #ed1c24;
        margin-top: 22px;
        text-transform: capitalize;
    }

    .description {
        text-align: center;
        font-size: 14px;
        color: #444;
        width: 80%;
        margin: 14px auto 40px auto;
        line-height: 1.7;
    }

    .training-title {
        font-weight: 700;
        color: #222;
    }

    .footer-section {
        margin-top: 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sign-box {
        text-align: center;
        width: 40%;
    }

    .sign-line {
        border-top: 1px solid #999;
        width: 70%;
        margin: 0 auto;
        padding-top: 8px;
    }

    .sign-label {
        font-size: 12px;
        color: #777;
        margin-top: 3px;
    }

    .admin-name {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-top: 6px;
    }

    .date-box {
        width: 40%;
        text-align: center;
    }

    .seal {
        position: absolute;
        top: 50%;
        right: -10px;
        transform: translateY(-50%);
        background: #ed1c24;
        color: white;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        border: 4px solid #fff;
        box-shadow: 0 0 8px rgba(0, 0, 0, .15);
    }

    .logo-top {
        position: absolute;
        top: 28px;
        right: 28px;
        width: 150px;
    }
</style>
@if ($isFromIndex == 1)
    <div class="certificate-wrapper">

        <img src="{{ asset('lms-img/creditsaison-logo.svg') }}" class="logo-top">

        <div class="certificate-inner">

            <div class="heading-main">Certificate of Achievement</div>
            <div class="sub-text">This certificate is proudly presented to</div>

            <div class="username">{{ Auth::user()->fullname }}</div>

            <div class="description">
                This certificate acknowledges that <strong>{{ Auth::user()->fullname }}</strong>
                has successfully completed the digital training program
                <span class="training-title">Soft Skills</span>
                on <strong>25-11-2025</strong>.
                It is awarded in recognition of the learner’s dedication and completion of the required training
                content.
            </div>

            <div class="footer-section">
                <div class="date-box">
                    <div class="sign-line"></div>
                    <div class="sign-label">Date</div>
                    <div class="admin-name">25-11-2025</div>
                </div>

                <div class="sign-box">
                    <div class="sign-line"></div>
                    <div class="sign-label">Sr. Manager — Training & Development</div>
                    <div class="admin-name">Admin</div>
                </div>
            </div>

            <div class="seal">CERTIFIED<br>TRAINING</div>

        </div>
    </div>
@else
    <div class="certificate-wrapper">

        <img src="{{ asset('lms-img/creditsaison-logo.svg') }}" class="logo-top">

        <div class="certificate-inner">

            <div class="heading-main">Certificate of Achievement</div>
            <div class="sub-text">This certificate is proudly presented to</div>

            <div class="username">{{ Auth::user()->fullname }}</div>

            <div class="description">
                This certificate acknowledges that <strong>{{ Auth::user()->fullname }}</strong>
                has successfully completed the digital training program
                <span class="training-title">{{ $trainingData->title }}</span>
                on <strong>25-11-2025</strong>.
                It is awarded in recognition of the learner’s dedication and completion of the required training
                content.
            </div>

            <div class="footer-section">
                <div class="date-box">
                    <div class="sign-line"></div>
                    <div class="sign-label">Date</div>
                    <div class="admin-name">25-11-2025</div>
                </div>

                <div class="sign-box">
                    <div class="sign-line"></div>
                    <div class="sign-label">Sr. Manager — Training & Development</div>
                    <div class="admin-name">{{ $admin }}</div>
                </div>
            </div>

            <div class="seal">CERTIFIED<br>TRAINING</div>

        </div>
    </div>
@endif
