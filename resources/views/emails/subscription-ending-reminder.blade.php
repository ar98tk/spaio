<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Spaio subscription renews soon</title>
</head>
<body style="margin:0;background:#f3f5f8;color:#1e293b;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f5f8;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border:1px solid #d5d9e0;border-radius:24px;overflow:hidden;">
                    <tr>
                        <td style="background:#013228;padding:30px 34px;">
                            <img src="{{ asset('spaio-logo.png') }}" alt="Spaio" style="width:92px;height:auto;margin-bottom:18px;">
                            <h1 style="margin:0;color:#ffffff;font-size:28px;line-height:36px;font-weight:700;">
                                {{ $subscription->status === 'trialing' ? 'Your trial ends soon' : 'Your plan renews soon' }}
                            </h1>
                            <p style="margin:12px 0 0;color:#d7efe9;font-size:15px;line-height:24px;">Three days remain before the next billing milestone.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:26px;">Hi {{ $user->name }},</p>
                            <p style="margin:0 0 22px;font-size:16px;line-height:26px;">
                                {{ $subscription->tenant?->business_name }} is currently on the <strong>{{ $subscription->plan?->name }}</strong> plan.
                                The {{ $subscription->status === 'trialing' ? 'trial' : 'current billing period' }} ends on
                                <strong>{{ $endsAt?->format('M d, Y') ?? 'the scheduled renewal date' }}</strong>.
                            </p>
                            <div style="background:#f0fdfa;border:1px solid #0d9488;border-radius:18px;padding:18px;color:#0f766e;font-size:15px;line-height:24px;">
                                Review your billing page now to keep the website, templates, bookings, and dashboard access active.
                            </div>
                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin:28px 0;">
                                <tr>
                                    <td style="border-radius:18px;background:#0d9488;">
                                        <a href="{{ $billingUrl }}" style="display:inline-block;padding:16px 28px;color:#ffffff;font-size:16px;font-weight:700;text-decoration:none;border-radius:18px;">Review Billing</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0;font-size:14px;line-height:23px;color:#64748b;">
                                This reminder is sent once, three days before the subscription milestone.
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
