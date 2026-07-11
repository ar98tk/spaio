<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Spaio invoice</title>
</head>
<body style="margin:0;background:#f3f5f8;color:#1e293b;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f5f8;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border:1px solid #d5d9e0;border-radius:24px;overflow:hidden;">
                    <tr>
                        <td style="background:#013228;padding:30px 34px;">
                            <img src="{{ asset('spaio-logo.png') }}" alt="Spaio" style="width:92px;height:auto;margin-bottom:18px;">
                            <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:36px;font-weight:700;">Your invoice is ready</h1>
                            <p style="margin:12px 0 0;color:#d7efe9;font-size:15px;line-height:24px;">A PDF copy is attached for your records.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:26px;">Hi {{ $user->name }},</p>
                            <p style="margin:0 0 22px;font-size:16px;line-height:26px;">
                                Your Spaio subscription was updated for {{ $subscription->tenant?->business_name }}. We attached invoice <strong>{{ $invoice['number'] }}</strong> to this email.
                            </p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #d5d9e0;border-radius:18px;overflow:hidden;">
                                <tr>
                                    <td style="padding:16px 18px;background:#f3f5f8;color:#64748b;font-size:13px;font-weight:700;text-transform:uppercase;">Plan</td>
                                    <td style="padding:16px 18px;text-align:right;font-size:15px;font-weight:700;color:#1e293b;">{{ $subscription->plan?->name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 18px;border-top:1px solid #d5d9e0;color:#64748b;font-size:13px;font-weight:700;text-transform:uppercase;">Billing cycle</td>
                                    <td style="padding:16px 18px;border-top:1px solid #d5d9e0;text-align:right;font-size:15px;font-weight:700;color:#1e293b;">{{ ucfirst($invoice['billing_cycle']) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 18px;border-top:1px solid #d5d9e0;color:#64748b;font-size:13px;font-weight:700;text-transform:uppercase;">Amount</td>
                                    <td style="padding:16px 18px;border-top:1px solid #d5d9e0;text-align:right;font-size:20px;font-weight:800;color:#0d9488;">${{ number_format($invoice['amount'], 2) }}</td>
                                </tr>
                            </table>
                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin:28px 0;">
                                <tr>
                                    <td style="border-radius:18px;background:#0d9488;">
                                        <a href="{{ route('panel.billing') }}" style="display:inline-block;padding:16px 28px;color:#ffffff;font-size:16px;font-weight:700;text-decoration:none;border-radius:18px;">Open Billing</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0;font-size:14px;line-height:23px;color:#64748b;">
                                Keep this email for accounting. You can also download the invoice anytime from your Spaio billing page.
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
