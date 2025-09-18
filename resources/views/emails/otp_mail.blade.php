<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 24px;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .otp-code {
            display: inline-block;
            background-color: #f7f7f7;
            padding: 10px 15px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
            text-align: center;
        }

        .footer a {
            color: #0047AB;
            text-decoration: none;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #0047AB;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h4>Reset Password OTP</h4>
            <p>Dear {{ $trainerOlms }},</p>
            <p>You have recieved requested to reset password from {{ $userdetail }}. Please use the OTP code below to
                complete the process:</p>
        <div class="otp-code">
            {{ $otp }}
        </div>

        {{-- <p>Please do not share this OTP with anyone. It will expire in 10 minutes.</p> --}}

        {{-- <p>If you did not request this password reset, please ignore this email or contact our support team.</p> --}}

        <div class="footer">
            <p>Thank you,<br>Team APB LMS</p>
            {{-- <p>If you have any questions, please feel free to <a href="mailto:support@example.com">contact us</a>.</p> --}}
        </div>
    </div>

</body>

</html>
