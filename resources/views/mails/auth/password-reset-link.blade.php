<html lang="en" style="background-color: #f3f4f8; font-size: 0; line-height: 0;"><head xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" style="font-family: Helvetica,Arial,sans-serif;">
    <meta charset="UTF-8" style="font-family: Helvetica,Arial,sans-serif;">
    <title style="font-family: Helvetica,Arial,sans-serif;">Neayi</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" style="font-family: Helvetica,Arial,sans-serif;">
    <meta name="viewport" content="width=device-width" style="font-family: Helvetica,Arial,sans-serif;">
</head>

<body style="-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; background-color: #f3f4f8; box-sizing: border-box; color: #0a0a0a; font-family: Helvetica,Arial,sans-serif; font-size: 14px; font-weight: 400; line-height: 1.43; min-width: 600px; text-align: left; width: 100% !important; margin: 0; padding: 0;" bgcolor="#f3f4f8">
<div style="display: none; max-height: 0px; overflow: hidden; mso-hide:all;"> ‌ ‌ ‌ </div>
<table align="center" width="800" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; font-family: Helvetica,Arial,sans-serif; max-width: 800px; min-width: 600px; text-align: left; vertical-align: top; padding: 0;">
    <tbody>
    <tr style="font-family: Helvetica,Arial,sans-serif; text-align: left; vertical-align: top; padding: 0;" align="left">
        <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #0a0a0a; font-family: Helvetica,Arial,sans-serif; font-size: 14px; font-weight: 400; hyphens: auto; line-height: 1.43; text-align: left; vertical-align: top; word-wrap: break-word; margin: 0; padding: 43px 0 0;" align="left" valign="top">
            <div style="background-color: #fff; border-radius: 8px; font-family: Helvetica,Arial,sans-serif;">
                <div style="font-family: Helvetica,Arial,sans-serif; height: 100%; min-height: 100px; padding: 0 40px;">
                    <table style="border-collapse: collapse; border-spacing: 0; font-family: Helvetica,Arial,sans-serif; text-align: left; vertical-align: top; width: 100%; padding: 0;">
                        <tbody>
                        <tr style="font-family: Helvetica,Arial,sans-serif; text-align: left; vertical-align: top; padding: 0;" align="left">
                            <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #0a0a0a; font-family: Helvetica,Arial,sans-serif; font-size: 14px; font-weight: 400; hyphens: auto; line-height: 1.43; text-align: left; vertical-align: top; width: 50%; word-wrap: break-word; margin: 0; padding: 32px 0 0;" align="left" valign="top">
                                <a style="color: #2a79ff; font-family: Helvetica,Arial,sans-serif; font-weight: 400; line-height: 1.43; text-align: left; text-decoration: none; margin: 0; padding: 0;" href="{{$url}}" target="_blank"><img src="{{asset('images/logo-triple-performance.png')}}" style="-ms-interpolation-mode: bicubic; clear: both; display: block; font-family: Helvetica,Arial,sans-serif; height: 50px; max-height: 100%; max-width: 100%; outline: 0; text-decoration: none; width: auto; border: none;"></a>
                            </td>
                            <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #0a0a0a; font-family: Helvetica,Arial,sans-serif; font-size: 14px; font-weight: 400; hyphens: auto; line-height: 1.43; text-align: right; vertical-align: top; width: 50%; word-wrap: break-word; margin: 0; padding: 26px 0 0;" align="right" valign="top">
                                <a href="https://wiki.tripleperformance.fr/" target="_blank" style="background-color: #fff; border-radius: 4px; box-sizing: border-box; color: #050038 !important; cursor: pointer; display: inline-block; font-family: Helvetica,Arial,sans-serif; font-size: 16px !important; font-stretch: normal; font-style: normal; font-weight: 400; letter-spacing: normal; text-align: center; text-decoration: none; white-space: nowrap; margin: 0; padding: 14px; border: 1px solid #050038;"><span style="font-family: Helvetica,Arial,sans-serif;">Aller à la plateforme<br>Triple&nbsp;Performance</span></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="font-family: Helvetica,Arial,sans-serif;">
                    <div style="font-family: Helvetica,Arial,sans-serif; background: no-repeat center / 100% auto; padding: 40px 40px 36px;">
                        <div style="color: #050038; font-family: Helvetica,Arial,sans-serif; font-size: 42px !important; font-stretch: normal; font-style: normal; font-weight: 700; letter-spacing: normal; line-height: 1.24;">
                            {{ __('auth.reset_password') }}
                        </div>
                        <div style="color: #050038; font-family: Helvetica,Arial,sans-serif; font-size: 20px !important; font-stretch: normal; font-style: normal; font-weight: 400; letter-spacing: normal; line-height: 1.4; margin-top: 16px; opacity: .6;">
                            {{ __('auth.mail_reset_password_line1') }}
                        </div>
                        <a href="{{$url}}" target="_blank" style="padding:0 20px; background-color: #3f53d9; border-radius: 4px; box-sizing: border-box; color: #fff !important; cursor: pointer; display: inline-block; font-family: Helvetica,Arial,sans-serif; font-size: 20px !important; font-stretch: normal; font-style: normal; font-weight: 400; height: 60px; letter-spacing: normal; line-height: 60px !important; text-align: center; text-decoration: none; white-space: nowrap; margin: 24px 0 0; border: none;">
                            {{ __('auth.mail_reset_password_line2') }}
                        </a>
                        <br/>
                        <br/>
                        {{ __('auth.mail_reset_password_line3', ['minutes' =>$validity])}}
                    </div>

                    <div style="background-color: #e1e0e7; font-family: Helvetica,Arial,sans-serif; height: 1px;"></div>
                </div>
            </div>
            <div style="font-family: Helvetica,Arial,sans-serif;">
                <table style="border-collapse: collapse; border-spacing: 0; font-family: Helvetica,Arial,sans-serif; text-align: left; vertical-align: top; padding: 0;">
                    <tbody>
                    <tr style="font-family: Helvetica,Arial,sans-serif; text-align: left; vertical-align: top; padding: 0;" align="left">
                        <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #0a0a0a; font-family: Helvetica,Arial,sans-serif; font-size: 14px; font-weight: 400; hyphens: auto; line-height: 1.43; text-align: left; vertical-align: top; word-wrap: break-word; margin: 0; padding: 0;" align="left" valign="top">
                            <table style="border-collapse: collapse; border-spacing: 0; font-family: Helvetica,Arial,sans-serif; text-align: left; vertical-align: top; width: 100%; padding: 0;">
                                <tbody style="font-family: Helvetica,Arial,sans-serif;">
                                <tr style="font-family: Helvetica,Arial,sans-serif; text-align: left; vertical-align: top; padding: 0;" align="left">
                                    <td height="80px" style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #0a0a0a; font-family: Helvetica,Arial,sans-serif; font-size: 80px; font-weight: 400; hyphens: auto; line-height: 80px; mso-line-height-rule: exactly; text-align: left; vertical-align: top; word-wrap: break-word; margin: 0; padding: 0;" align="left" valign="top">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
    </tbody>
</table>
<div style="display: none; white-space: nowrap; font: 15px courier;">
</div>
</body>
</html>
