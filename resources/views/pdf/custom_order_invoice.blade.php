<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 15mm; }
    * { box-sizing: border-box; }
    body { margin: 0; padding: 0; font-family: 'DejaVu Sans', sans-serif; color: #222; font-size: 10pt; }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 0.6mm solid #111;
        padding-bottom: 6mm;
        margin-bottom: 6mm;
    }
    .brand-title {
        font-size: 20pt;
        font-weight: bold;
        margin: 0;
    }
    .brand-sub {
        font-size: 9pt;
        color: #777;
        margin-top: 1mm;
    }
    .invoice-meta {
        text-align: right;
    }
    .invoice-meta h2 {
        font-size: 16pt;
        margin: 0 0 2mm 0;
        letter-spacing: 0.5pt;
    }
    .invoice-meta div {
        font-size: 9.5pt;
        color: #444;
    }

    .info-columns {
        display: flex;
        justify-content: space-between;
        gap: 8mm;
        margin-bottom: 8mm;
    }
    .info-box {
        width: 48%;
    }
    .info-box h6 {
        font-size: 9pt;
        font-weight: bold;
        letter-spacing: 0.2pt;
        color: #888;
        margin: 0 0 2mm 0;
    }
    .info-box p {
        margin: 0;
        line-height: 1.6;
        font-size: 9.5pt;
    }
    .info-box .party-name {
        font-size: 11pt;
        font-weight: bold;
        margin-bottom: 1mm;
        display: block;
    }

    table.items {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6mm;
    }
    table.items th {
        text-align: left;
        font-size: 9pt;
        font-weight: bold;
        color: #555;
        border-bottom: 0.4mm solid #ccc;
        padding: 3mm 2mm;
    }
    table.items td {
        padding: 3mm 2mm;
        border-bottom: 0.2mm solid #eee;
        font-size: 9.5pt;
    }
    table.items th.num, table.items td.num {
        text-align: right;
    }

    .totals {
        width: 100%;
        margin-top: 4mm;
        margin-bottom: 8mm;
    }
    .totals table {
        margin-left: auto;
        width: 75mm;
        border-collapse: collapse;
    }
    .totals td {
        padding: 1.5mm 2mm;
        font-size: 10pt;
    }
    .totals .total-row td {
        border-top: 0.4mm solid #111;
        font-weight: bold;
        font-size: 12pt;
        padding-top: 3mm;
    }

    .payment-box {
        border: 0.4mm solid #ddd;
        border-radius: 2mm;
        padding: 5mm 6mm;
        background: #fafafa;
        margin-bottom: 8mm;
    }
    .payment-box h6 {
        font-size: 9pt;
        font-weight: bold;
        letter-spacing: 0.2pt;
        color: #888;
        margin: 0 0 3mm 0;
    }
    .payment-grid {
        display: flex;
        flex-wrap: wrap;
    }
    .payment-grid div {
        width: 50%;
        font-size: 9.5pt;
        margin-bottom: 2mm;
    }
    .payment-grid span.label {
        color: #777;
        display: block;
        font-size: 8pt;
        font-weight: bold;
    }
    .payment-note {
        font-size: 8.5pt;
        color: #666;
        font-style: italic;
        margin-top: 2mm;
    }

    .thank-you {
        text-align: center;
        font-size: 11pt;
        font-weight: bold;
        margin-bottom: 3mm;
    }
    .footer {
        font-size: 8pt;
        color: #999;
        text-align: center;
    }
</style>
</head>
<body>
@php
    $currency = $line_items[0]['currency'] ?? '₾';
    $hasBankInfo = !empty($company['bank_iban']) || !empty($company['bank_name']);
@endphp

<div class="header">
    <div>
        <p class="brand-title">{{ $company['name'] }}</p>
        @if(!empty($company['address']))
            <div class="brand-sub">{{ $company['address'] }}</div>
        @endif
        @if(!empty($company['tax_id']))
            <div class="brand-sub">{{ $labels['tax_id'] }}: {{ $company['tax_id'] }}</div>
        @endif
    </div>
    <div class="invoice-meta">
        <h2>{{ $labels['invoice'] }}</h2>
        <div>{{ $labels['invoice_number'] }}: {{ $invoice_number }}</div>
        <div>{{ $labels['date'] }}: {{ $invoice_date }}</div>
    </div>
</div>

<div class="info-columns">
    <div class="info-box">
        <h6>{{ $labels['seller'] }}</h6>
        <p>
            <span class="party-name">{{ $company['name'] }}</span>
            @if(!empty($company['address'])) {{ $company['address'] }}<br> @endif
            @if(!empty($company['phone'])) {{ $labels['phone'] }}: {{ $company['phone'] }}<br> @endif
            @if(!empty($company['email'])) {{ $labels['email'] }}: {{ $company['email'] }}<br> @endif
        </p>
    </div>
    <div class="info-box">
        <h6>{{ $labels['billed_to'] }}</h6>
        <p>
            <span class="party-name">{{ $buyer['name'] ?? '' }} {{ $buyer['surname'] ?? '' }}</span>
            @if(!empty($buyer['address'])) {{ $buyer['address'] }}@if(!empty($buyer['city'])), {{ $buyer['city'] }}@endif<br> @endif
            @if(!empty($buyer['phone'])) {{ $labels['phone'] }}: {{ $buyer['phone'] }}<br> @endif
            @if(!empty($buyer['email'])) {{ $labels['email'] }}: {{ $buyer['email'] }}<br> @endif
        </p>
    </div>
</div>

<table class="items">
    <thead>
        <tr>
            <th>{{ $labels['product'] }}</th>
            <th>{{ $labels['option'] }}</th>
            <th class="num">{{ $labels['qty'] }}</th>
            <th class="num">{{ $labels['unit_price'] }}</th>
            <th class="num">{{ $labels['line_total'] }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($line_items as $item)
        <tr>
            <td>{{ $item['title'] }}</td>
            <td>{{ $item['option'] ?: '—' }}</td>
            <td class="num">{{ $item['quantity'] }}</td>
            <td class="num">{{ number_format($item['unit_price'], 2) }} {{ $item['currency'] }}</td>
            <td class="num">{{ number_format($item['line_total'], 2) }} {{ $item['currency'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totals">
    <table>
        <tr class="total-row">
            <td>{{ $labels['total'] }}</td>
            <td class="num" style="text-align:right;">{{ number_format($total, 2) }} {{ $currency }}</td>
        </tr>
    </table>
</div>

@if($hasBankInfo)
<div class="payment-box">
    <h6>{{ $labels['payment_details'] }}</h6>
    <div class="payment-grid">
        @if(!empty($company['bank_name']))
        <div>
            <span class="label">{{ $labels['bank'] }}</span>
            {{ $company['bank_name'] }}
        </div>
        @endif
        @if(!empty($company['bank_account_name']))
        <div>
            <span class="label">{{ $labels['account_holder'] }}</span>
            {{ $company['bank_account_name'] }}
        </div>
        @endif
        @if(!empty($company['bank_iban']))
        <div>
            <span class="label">{{ $labels['iban'] }}</span>
            {{ $company['bank_iban'] }}
        </div>
        @endif
        @if(!empty($company['bank_code']))
        <div>
            <span class="label">{{ $labels['bank_code'] }}</span>
            {{ $company['bank_code'] }}
        </div>
        @endif
    </div>
    <div class="payment-note">{{ $labels['payment_note'] }}</div>
</div>
@endif

<div class="thank-you">{{ $labels['thank_you'] }}</div>
<div class="footer">{{ $labels['generated_by'] }}</div>
</body>
</html>
