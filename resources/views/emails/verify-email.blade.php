<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify your Spaio email</title>
</head>
<body style="margin:0;background:#f3f5f8;color:#1e293b;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f5f8;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border:1px solid #d5d9e0;border-radius:24px;overflow:hidden;">
                    <tr>
                        <td style="background:#013228;padding:32px;text-align:center;">
                            <img src="{{ asset('spaio-logo.png') }}" alt="Spaio" style="width:92px;height:auto;margin-bottom:18px;">
                            <h1 style="margin:0;color:#ffffff;font-size:30px;line-height:38px;font-weight:700;">Verify your email address</h1>
                            <p style="margin:12px 0 0;color:#d7efe9;font-size:15px;line-height:24px;">Secure your Spaio account and activate dashboard access.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:26px;">Hi {{ $user->name }},</p>
                            <p style="margin:0 0 16px;font-size:16px;line-height:26px;">
                                Welcome to Spaio{{ $tenant?->business_name ? ' for '.$tenant->business_name : '' }}. Please confirm your email address so we can protect your account and let you continue to your dashboard.
                            </p>
                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin:28px 0;">
                                <tr>
                                    <td style="border-radius:18px;background:#0d9488;">
                                        <a href="{{ $url }}" style="display:inline-block;padding:16px 28px;color:#ffffff;font-size:16px;font-weight:700;text-decoration:none;border-radius:18px;">Verify Email</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 16px;font-size:14px;line-height:23px;color:#64748b;">
                                This verification link will expire automatically. If you did not create a Spaio account, you can safely ignore this email.
                            </p>
                            <p style="margin:24px 0 0;font-size:14px;line-height:23px;color:#64748b;">
                                If the button does not work, copy and paste this link into your browser:<br>
                                <a href="{{ $url }}" style="color:#0d9488;word-break:break-all;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #d5d9e0;padding:22px 34px;text-align:center;color:#64748b;font-size:13px;line-height:21px;">
                            Spaio - Premium websites, booking, and growth tools for spa businesses.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
