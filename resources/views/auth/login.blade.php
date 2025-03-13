<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | iSagha E-commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Background Styling */
        body {
            background: url('https://source.unsplash.com/1600x900/?gold,jewelry') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Login Card */
        .login-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        /* Title Styling */
        .login-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        /* Social Buttons */
        .btn-social {
            border-radius: 50px;
            padding: 12px;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-social img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        /* Google Button */
        .btn-google {
            background: white;
            color: #000;
            border: 2px solid #db4437;
        }
        .btn-google:hover {
            background: #db4437;
            color: white;
        }

        /* Facebook Button */
        .btn-facebook {
            background: white;
            color: #000;
            border: 2px solid #1877f2;
        }
        .btn-facebook:hover {
            background: #1877f2;
            color: white;
        }

        /* Footer */
        .footer-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h3 class="login-title mb-4">Login to iSagha Task</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <a href="{{ route('social.login', 'google') }}" class="btn btn-social btn-google w-100 mb-3">
            <img src="{{ asset('images/google-logo.png') }}" alt="Google Logo"> Login with Google
        </a>
        <a href="{{ route('social.login', 'facebook') }}" class="btn btn-social btn-facebook w-100">
            <img src="{{ asset('images/facebook-logo.png') }}" alt="Facebook Logo"> Login with Facebook
        </a>
    </div>

</body>
</html>
