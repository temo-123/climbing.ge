<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 12mm 14mm; }
    * { box-sizing: border-box; }
    body { margin: 0; padding: 0; font-family: 'DejaVu Sans', sans-serif; color: #222; font-size: 10pt; }

    table.layout { width: 100%; border-collapse: collapse; }
    table.layout td { vertical-align: top; padding: 0; }
    .header {
        border-bottom: 0.6mm solid #111;
        padding-bottom: 4mm;
        margin-bottom: 5mm;
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
        margin-bottom: 5mm;
    }
    .info-box {
        width: 50%;
        padding-right: 6mm !important;
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
        padding: 2mm 2mm;
    }
    table.items td {
        padding: 2mm 2mm;
        border-bottom: 0.2mm solid #eee;
        font-size: 9.5pt;
    }
    table.items th.num, table.items td.num {
        text-align: right;
    }

    .totals {
        width: 100%;
        margin-top: 2mm;
        margin-bottom: 5mm;
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
        padding: 4mm 5mm;
        background: #fafafa;
        margin-bottom: 5mm;
    }
    .payment-box h6 {
        font-size: 9pt;
        font-weight: bold;
        letter-spacing: 0.2pt;
        color: #888;
        margin: 0 0 3mm 0;
    }
    .payment-grid div {
        display: inline-block;
        width: 48%;
        vertical-align: top;
        font-size: 9.5pt;
        margin-bottom: 2mm;
    }
    .meta-strip {
        border: 0.3mm solid #ddd;
        background: #fafafa;
        margin-bottom: 6mm;
    }
    .meta-strip td {
        padding: 2.5mm 3mm !important;
        font-size: 9.5pt;
    }
    .meta-strip .label {
        display: block;
        color: #777;
        font-size: 8pt;
        font-weight: bold;
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

<table class="layout header"><tr>
    <td>
        <p class="brand-title">{{ $company['name'] }}</p>
        @if(!empty($company['address']))
            <div class="brand-sub">{{ $company['address'] }}</div>
        @endif
        @if(!empty($company['tax_id']))
            <div class="brand-sub">{{ $labels['tax_id'] }}: {{ $company['tax_id'] }}</div>
        @endif
    </td>
    <td class="invoice-meta">
        <h2>{{ $labels['invoice'] }}</h2>
        <div>{{ $labels['invoice_number'] }}: {{ $invoice_number }}</div>
        @if(!empty($order_number))
            <div>{{ $labels['order_number'] }}: #{{ $order_number }}</div>
        @endif
        <div>{{ $labels['date'] }}: {{ $invoice_date }}</div>
    </td>
</tr></table>

<table class="layout info-columns"><tr>
    <td class="info-box">
        <h6>{{ $labels['seller'] }}</h6>
        <p>
            <span class="party-name">{{ $company['name'] }}</span>
            @if(!empty($company['address'])) {{ $company['address'] }}<br> @endif
            @if(!empty($company['phone'])) {{ $labels['phone'] }}: {{ $company['phone'] }}<br> @endif
            @if(!empty($company['email'])) {{ $labels['email'] }}: {{ $company['email'] }}<br> @endif
        </p>
    </td>
    <td class="info-box">
        <h6>{{ $labels['billed_to'] }}</h6>
        <p>
            <span class="party-name">{{ $buyer['name'] ?? '' }} {{ $buyer['surname'] ?? '' }}</span>
            @if(!empty($buyer['address'])) {{ $buyer['address'] }}@if(!empty($buyer['city'])), {{ $buyer['city'] }}@endif<br> @endif
            @if(!empty($buyer['phone'])) {{ $labels['phone'] }}: {{ $buyer['phone'] }}<br> @endif
            @if(!empty($buyer['email'])) {{ $labels['email'] }}: {{ $buyer['email'] }}<br> @endif
        </p>
    </td>
</tr></table>

@if(!empty($delivery_method) || !empty($payment_method))
<table class="layout meta-strip"><tr>
    @if(!empty($delivery_method))
    <td>
        <span class="label">{{ $labels['delivery_method'] }}</span>
        {{ $delivery_method }}@if(!empty($pickup_from)) — {{ $labels['pickup_from'] }}: {{ $pickup_from }}@endif
    </td>
    @endif
    @if(!empty($payment_method))
    <td>
        <span class="label">{{ $labels['payment_method'] }}</span>
        {{ $payment_method }}
    </td>
    @endif
</tr></table>
@endif

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
            <td class="num">
                @if(($item['item_discount'] ?? 0) > 0 && !empty($item['list_price']))
                    <span style="text-decoration: line-through; color: #999; font-size: 8.5pt;">{{ number_format($item['list_price'], 2) }}</span><br>
                @endif
                {{ number_format($item['unit_price'], 2) }} {{ $item['currency'] }}
                @if(($item['item_discount'] ?? 0) > 0)
                    <br><span style="color: #c0392b; font-size: 8pt;">−{{ rtrim(rtrim(number_format($item['item_discount'], 2), '0'), '.') }}%</span>
                @endif
            </td>
            <td class="num">{{ number_format($item['line_total'], 2) }} {{ $item['currency'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totals">
    <table>
        @php $showShipping = !empty($show_shipping); @endphp
        @if(($discount_percent ?? 0) > 0 || $showShipping)
        <tr>
            <td>{{ $labels['subtotal'] }}</td>
            <td class="num" style="text-align:right;">{{ number_format($subtotal, 2) }} {{ $currency }}</td>
        </tr>
        @endif
        @if(($discount_percent ?? 0) > 0)
        <tr>
            <td>{{ $labels['discount'] }} (@if(!empty($partner_name)){{ $partner_name }}, @endif-{{ rtrim(rtrim(number_format($discount_percent, 2), '0'), '.') }}%)</td>
            <td class="num" style="text-align:right;">-{{ number_format($discount_amount, 2) }} {{ $currency }}</td>
        </tr>
        @endif
        @if($showShipping)
        <tr>
            <td>{{ $labels['shipping'] }}</td>
            <td class="num" style="text-align:right;">
                @if(($shipping ?? 0) > 0) {{ number_format($shipping, 2) }} {{ $currency }} @else {{ $labels['free'] }} @endif
            </td>
        </tr>
        @endif
        <tr class="total-row">
            <td>{{ $labels['total'] }}</td>
            <td class="num" style="text-align:right;">{{ number_format($total, 2) }} {{ $currency }}</td>
        </tr>
        @if(isset($paid_amount) && $paid_amount !== null)
        <tr>
            <td>{{ $labels['paid'] }}</td>
            <td class="num" style="text-align:right;">{{ number_format($paid_amount, 2) }} {{ $currency }}</td>
        </tr>
        @endif
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
