<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { color: #1a1a2e; font-family: DejaVu Sans, sans-serif; font-size: 13px; line-height: 1.6; }
        .header { border-bottom: 2px solid #013228; padding-bottom: 22px; }
        .logo { width: 150px; }
        .title { float: right; text-align: right; }
        h1 { margin: 0; color: #013228; font-size: 30px; }
        .muted { color: #667085; }
        .grid { clear: both; margin-top: 34px; width: 100%; }
        .box { border: 1px solid #d8dde1; border-radius: 10px; padding: 18px; vertical-align: top; width: 48%; }
        .right { margin-left: 4%; }
        table { border-collapse: collapse; margin-top: 34px; width: 100%; }
        th { background: #013228; color: #fff; padding: 12px; text-align: left; }
        td { border-bottom: 1px solid #d8dde1; padding: 12px; }
        .total { margin-top: 24px; text-align: right; }
        .total strong { color: #013228; font-size: 24px; }
        .footer { border-top: 1px solid #d8dde1; color: #667085; margin-top: 48px; padding-top: 16px; text-align: center; }
        .badge { background: #e3ffcd; border-radius: 999px; color: #013228; display: inline-block; font-weight: bold; padding: 5px 10px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header">
        <img class="logo" src="{{ $logoPath }}" alt="Spaio">
        <div class="title">
            <h1>Invoice</h1>
            <div class="muted">{{ $invoice['number'] }}</div>
            <div class="badge">{{ $invoice['status'] }}</div>
        </div>
    </div>

    <table class="grid">
        <tr>
            <td class="box">
                <strong>Billed to</strong><br>
                {{ $tenant->business_name }}<br>
                {{ $owner?->name }}<br>
                {{ $owner?->email }}<br>
                {{ $tenant->address }}
            </td>
            <td class="box right">
                <strong>Invoice details</strong><br>
                Issued: {{ $invoice['issued_at']?->format('M d, Y') }}<br>
                Billing cycle: {{ ucfirst($invoice['billing_cycle']) }}<br>
                Period ends: {{ $invoice['period_ends_at']?->format('M d, Y') ?? 'Not set' }}<br>
                Generated: {{ $invoice['generated_at']->format('M d, Y H:i') }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Plan</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice['description'] }}</td>
                <td>{{ $subscription->plan?->name ?? 'Starter' }}</td>
                <td style="text-align: right;">${{ number_format($invoice['amount'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total<br>
        <strong>${{ number_format($invoice['amount'], 2) }}</strong>
    </div>

    <div class="footer">
        Spaio - Premium website, booking, and growth tools for spa businesses.
    </div>
</body>
</html>
