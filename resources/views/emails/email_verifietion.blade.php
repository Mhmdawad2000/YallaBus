<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>التحقق من البريد الإلكتروني - YallaBus</title>
</head>

<body style="background-color: #f4f4f4; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; margin: 40px auto; border-collapse: collapse;">
                    <!-- Header -->
                    <tr>
                        <td
                            style="background-color: #00bfa5; color: #ffffff; padding: 20px; text-align: center; font-size: 22px;">
                            YallaBus - التحقق من البريد الإلكتروني
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px; color: #333333; font-size: 16px; line-height: 1.6;">
                            <p style="margin: 0 0 15px;">مرحبًا <strong>{{ $content['name'] }}</strong>،</p>
                            <p style="margin: 0 0 15px;">شكرًا لتسجيلك في <strong>YallaBus</strong>.</p>
                            <p style="margin: 0 0 15px;">رمز التحقق الخاص بك:</p>
                            <p style="font-size: 24px; font-weight: bold; color: #00bfa5; margin: 0 0 20px;">
                                {{ $content['code'] }}</p>
                            <p style="margin: 0 0 20px;">أو يمكنك الضغط على الزر التالي للتحقق مباشرةً:</p>

                            <p>
                                <a href="{{ $content['link'] }}"
                                    style="background-color: #00bfa5; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">تأكيد
                                    البريد الإلكتروني</a>
                            </p>

                            <p style="margin-top: 30px;">إذا لم تطلب هذا البريد، يمكنك تجاهله بأمان.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f0f0f0; color: #666666; text-align: center; padding: 15px; font-size: 13px;">
                            © {{ date('Y') }} YallaBus. جميع الحقوق محفوظة.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
