<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Nunito+Sans');

        :root {
            --blue: #0e0620;
            --white: #fff;
            --green: #2ccf6d;
        }

        body,
        html {
            height: 100%;
            margin: 0;
        }

        .bg {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .error-container {
            text-align: center;
            max-width: 600px;
            margin: auto;
            padding: 20px;
        }

        .error-code {
            font-size: 72px;
            font-weight: bold;
            color: #dc3545;
        }

        .error-message {
            font-size: 24px;
            margin: 20px 0;
        }

        .error-description {
            font-size: 16px;
            color: #6c757d;
        }

        .btn-home {}

        .btn {
            z-index: 1;
            overflow: hidden;
            background: transparent;
            position: relative;
            padding: 8px 50px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1em;
            letter-spacing: 2px;
            transition: 0.2s ease;
            font-weight: bold;
            margin: 5px 0px;
        }

        .btn.green {
            border: 4px solid var(--green);
            color: var(--blue);
        }

        .btn.green:before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 0%;
            height: 100%;
            background: var(--green);
            z-index: -1;
            transition: 0.2s ease;
        }

        .btn.green:hover {
            color: var(--white);
            background: var(--green);
            transition: 0.2s ease;
        }

        .btn.green:hover:before {
            width: 100%;
        }

        .home-btn {
            margin: 35px 0;

        }

        @media (max-width: 767px) {
            .error-code {
                font-size: 48px;
            }

            .error-message {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="bg">
        <div class="error-container">
            <div class="error-code">404</div>
            <div class="error-message">Page Not Found</div>
            <div class="error-description">
                We're sorry, but the page you are looking for may have been removed, renamed, or is temporarily
                unavailable.
            </div>
            <div class="home-btn">
                <a class="btn green" href="{{ url('/') }}">Go to Home</a>
            </div>
        </div>
    </div>
</body>

</html>
