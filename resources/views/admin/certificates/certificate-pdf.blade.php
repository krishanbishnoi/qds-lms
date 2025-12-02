<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>certificate</title>
    {{-- <link
        href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Great+Vibes&display=swap"
        rel="stylesheet"> --}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <style>
        .certificateMan {
            --bs-modal-width: 720px;
        }
    </style>
</head>


<body>
    <table
        style="background-image: url('{{ asset('front/img/backgroundimage.png') }}'); background-repeat: no-repeat;width: 100%;background-position: left top;background-size: cover;padding: 0px 32px 32px 32px;">
        <tr>
            <td style="padding-top: 60px;padding-left: 20px;font-size: 35px;font-weight:700;color: #ed1c24;">
                <div style="font-family: 'Cinzel Decorative', cursive;text-transform:uppercase;">
                    Certificate</div>
            </td>
            <td align="right" style="padding-top: 30px;padding-right: 25px;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents('https://lms.qdegrees.com/lms-img/creditsaison-logo.png')) }}"
                    alt="logo" width="100">
            </td>
        </tr>
        <tr>
            <td colspan="2"
                style="font-size: 16px;font-weight: 700;text-transform: uppercase;padding-left: 6px;color: #323232;padding-top: 40px;">
                of achievement - training COC</td>
        </tr>
        <tr align="center">
            <td colspan="2" width="100%"
                style="text-transform: uppercase;font-size: 16px;font-weight: 700;color: #2c2c2c;font-family: sans-serif;font-size: 12px;    font-weight: 500;padding-top: 30px;">

                proudly presented to :
            </td>

        </tr>
        <tr style="text-align: center;">
            <td colspan="2"
                style="font-weight: 600;font-family: 'Great Vibes', cursive;;font-size: 35px;color: #ed1c24;padding-top: 20px;">
                {{ Auth::user()->name }}
                <p
                    style="padding-top: 20px; font-family: sans-serif;color: #5c5a59;font-size: 12px;font-weight: 500;margin-top: 20px;width: 70%;margin: auto;padding-bottom: 40px;">
                    This certificate acknowledges that <strong>{{ Auth::user()->name }}</strong> has successfully completed
                    the digital training program
                    <strong>COC</strong> on <strong>Soft Skills</strong>, delivered via the LMS
                    platform at QDegrees.
                    <br><br>
                    It is awarded in recognition of the learner’s active participation and completion of the required
                    training content.
                </p>
            </td>
        </tr>
        <tr>
            <td width="50%" style="padding-bottom: 80px;">
                <span
                    style="display: grid;text-align: center;font-family: sans-serif;font-size: 13px;font-weight: normal;color: #5c5a59;">Date<br>
                    <b style="font-weight: 500;font-size: 16px;color: #474645;">25-10-2025</b></span>
            </td>
            <td width="50%" style="padding-bottom: 80px;">
                <span
                    style="display: grid;text-align: center;font-family: sans-serif;font-size: 13px;font-weight: normal;color: #5c5a59;">Sr.
                    Manager<br>Training & Development<br>
                    <b style="font-weight: 500;font-size: 16px;color: #474645;">Qdegrees</b></span>
            </td>
        </tr>
    </table>
</body>

</html>
