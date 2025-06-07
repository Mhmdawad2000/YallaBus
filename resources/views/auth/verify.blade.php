<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>التحقق من البريد الإلكتروني - YallaBus</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #00bfa5;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }

        .content {
            padding: 30px;
            font-size: 18px;
            color: #333333;
            line-height: 1.6;
        }

        .footer {
            padding: 20px;
            background-color: #f0f0f0;
            text-align: center;
            font-size: 14px;
            color: #666666;
        }

        .btn {
            display: inline-block;
            background-color: #00bfa5;
            color: #ffffff !important;
            padding: 12px 24px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #009e8a;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            YallaBus - التحقق من البريد الإلكتروني
        </div>
        <div class="content">
            <p>مرحباً👋</p>
            <p>{{ $message }}</p>
        
        <div class="footer">
            © {{ date('Y') }} YallaBus. جميع الحقوق محفوظة.
        </div>
    </div>
</body>

</html>
