
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Код подтверждения</title>
    <style>
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 20px !important; }
            .code { font-size: 28px !important; letter-spacing: 6px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f6f9;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="480" class="container" style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); overflow: hidden;">
                    
                    <tr>
                        <td style="background: linear-gradient(135deg, #4071CB 0%, #5B8DEF 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 600; letter-spacing: 0.5px;">
                                Подтверждение email
                            </h1>
                            <p style="color: rgba(255,255,255,0.85); margin: 10px 0 0; font-size: 14px;">
                                Завершение регистрации
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #333; font-size: 15px; line-height: 1.6; margin: 0 0 24px;">
                                Здравствуйте! Вы начали регистрацию на нашем сайте. Для завершения введите код подтверждения:
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="background-color: #f0f5ff; border: 2px dashed #4071CB; border-radius: 12px; padding: 24px; text-align: center;">
                                        <p style="color: #666; font-size: 13px; margin: 0 0 12px; text-transform: uppercase; letter-spacing: 1px;">
                                            Ваш код подтверждения
                                        </p>
                                        <div class="code" style="color: #4071CB; font-size: 36px; font-weight: 700; letter-spacing: 10px; font-family: 'Courier New', monospace;">
                                            {{ $code }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 24px;">
                                <tr>
                                    <td style="background-color: #fff8e1; border-radius: 8px; padding: 14px 18px;">
                                        <p style="color: #856404; font-size: 13px; margin: 0; line-height: 1.5;">
                                            <strong>Важно:</strong> код действителен в течение <strong>15 минут</strong>. Если вы не запрашивали регистрацию, просто проигнорируйте это письмо.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <hr style="border: none; border-top: 1px solid #eee; margin: 32px 0;">

                            <p style="color: #999; font-size: 12px; line-height: 1.5; margin: 0; text-align: center;">
                                Это автоматическое письмо, пожалуйста, не отвечайте на него.<br>
                                Если у вас возникли вопросы, обратитесь в поддержку: <a href="mailto:support@abdulin.hhos.ru" style="color: #4071CB; text-decoration: none;">support@abdulin.hhos.ru</a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #fafafa; padding: 20px 30px; text-align: center; border-top: 1px solid #eee;">
                            <p style="color: #aaa; font-size: 12px; margin: 0;">
                                © {{ date('Y') }} Все права защищены
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>