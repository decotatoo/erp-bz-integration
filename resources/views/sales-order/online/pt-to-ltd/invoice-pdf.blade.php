<!DOCTYPE html>
<html>

@php
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
@endphp

<head>
    <title>Export Invoice {{ $sales_order->uid }}</title>
    <style type="text/css">
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td,
        article,
        aside,
        canvas,
        details,
        embed,
        figure,
        figcaption,
        footer,
        header,
        hgroup,
        menu,
        nav,
        output,
        ruby,
        section,
        summary,
        time,
        mark,
        audio,
        video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
            font-family: sans-serif;
        }

        /* HTML5 display-role reset for older browsers */
        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        menu,
        nav,
        section {
            display: block;
        }

        body {
            line-height: 1;
        }

        ol,
        ul {
            list-style: none;
        }

        blockquote,
        q {
            quotes: none;
        }

        blockquote:before,
        blockquote:after,
        q:before,
        q:after {
            content: '';
            content: none;
        }

        /* table {
            border-collapse: collapse;
            border-spacing: 0;
        } */

        .content {
            margin-top: 30px;
            margin-left: 40px;
            margin-right: 40px;
        }

        .content .header {
            text-align: center;
        }

        .content .header .company-name {
            font-size: 16px;
        }

        .content .header .company-address {
            margin-top: 7px;
            font-size: 11px;
        }

        .bold {
            font-weight: bold;
        }

        .content .header .title {
            font-size: 14px;
        }

        .content .header .period {
            font-size: 13px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .employee-information {
            letter-spacing: 0.1px;
            margin-top: 19px;
            margin-bottom: 19px;
        }

        .employee-information::after {
            clear: both;
            height: 0;
            width: 100%;
            content: '';
            display: block;
        }

        .question {
            font-size: 11px;
            margin: 5px 0;
            display: inline-block;
        }

        .left .question {
            width: 40%;
        }

        .right .question {
            width: 35%;
        }

        .answer {
            font-size: 11px;
        }

        .left {
            float: left;
            width: 50%;
        }

        .right {
            float: right;
            width: 35%;
        }

        .detail-left {
            float: left;
            width: 60%;
        }

        .detail-right {
            float: right;
            width: 40%;
        }

        .detail-left .bold,
        .detail-right .bold {
            font-size: 11px;
        }

        .detail-header {
            letter-spacing: 0.1px;
            margin-top: -2px;
            margin-bottom: -2px;
        }

        .detail-header::after {
            clear: both;
            height: 0;
            width: 100%;
            content: '';
            display: block;
        }

        .detail-content {
            letter-spacing: 0.1px;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 11px;
        }

        .detail-content::after {
            clear: both;
            height: 0;
            width: 100%;
            content: '';
            display: block;
        }

        .detail-content .question {
            font-size: 11px;
            margin: 4px 0;
            display: inline-block;
        }

        .detail-left .question {
            width: 40%;
        }

        .detail-right .question {
            width: 60%;
        }

        .detail-footer {
            letter-spacing: 0.1px;
            margin-top: -10px;
            margin-bottom: -4px;
        }

        .detail-footer::after {
            clear: both;
            height: 0;
            width: 100%;
            content: '';
            display: block;
        }

        .footer-left {
            float: left;
            width: 60%;
        }

        .footer-right {
            float: right;
            width: 40%;
        }

        .footer-left .question {
            width: 30%;
        }

        .footer-right .question {
            width: 35%;
        }

        .footer {
            letter-spacing: 0.1px;
            margin-top: 15px;
        }

        .footer .question {
            font-size: 11px;
            margin: 3px 0;
            display: inline-block;
        }

        .footer::after {
            clear: both;
            height: 0;
            width: 100%;
            content: '';
            display: block;
        }

        .center {
            margin: auto;
            display: block;
        }

        /* table { */
        /* border-collapse: collapse; */
        /* border-spacing: 0; */
        /* } */

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 13px;
        }

        th {
            padding: 15px;
            border-bottom: 2px solid black;
            margin-bottom: 20px;
        }

        td {
            padding: 15px;
        }

        .page-break {
            page-break-after: always;
        }

        .left-signature {
            float: left;
            width: 30%;
        }
    </style>
</head>

<body>

    <div class="content">
        <div style="text-align: center; margin-top: 10px">
            <img src="{{ asset('images/logo.png') }}" alt="" class="">
        </div>
        <div style="text-align: center; margin-top: 10px">
            <p class="bold">INVOICE</p>
        </div>

        <div class="employee-information">
            <div class="left">
                <p class="bold">
                    {{ $company_pt->name }}
                </p>
                <p>
                    {!! Str::of($company_pt->address)->replaceMatches('/\n/', '<br>') !!}
                </p>
                @if ($company_pt->phone)
                Tel. {{ $company_pt->phone }}
                @endif
                @if ($company_pt->email)
                <p>
                    Email: {{ $company_pt->email }}
                </p>
                @endif
            </div>

            <div class="right">
                <p class="">Invoice Number: <span style="float: right;">{{ $sales_order->uid }}</span></p>
                <p class="">Invoice Date: <span style="float: right;">{{ Carbon::parse($sales_order->date_invoice_print)->format('Y-m-d') }}</span></p>
                <p class="">Payment: <span style="float: right;">{{ $sales_order->payment_method_title }}</span></p>
            </div>
        </div>

        <hr style="border: 1.1px solid black;">

        <div class="employee-information">
            <div class="left">
                <p class="bold">Bill To</p>
                <p>
                    {{ $company_ltd->name }}
                    <br>
                    {!! Str::of($company_ltd->address)->replaceMatches('/\n/', '<br>') !!}
                </p>
                @if ($company_ltd->phone)
                Tel. {{ $company_ltd->phone }}
                @endif
                @if ($company_ltd->email)
                <p>
                    Email: {{ $company_ltd->email }}
                </p>
                @endif
            </div>

            
            <div class="right">
                <p class="">SO Number: <span style="float: right;">{{ $sales_order->uid }}</span></p>
                <p class="">Carier: <span style="float: right;">{{ $sales_order->shipping_lines[0]['method_title'] }}</span></p>
                <p class="">Delivery Date: <span style="float: right;">{{ Carbon::parse($sales_order->date_shipment_shipped)->format('Y-m-d') }}</span></p>
            </div>
        </div>

        <hr style="border: 1.1px solid black;">

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Product code</th>
                        <th>Product description</th>
                        <th>Size</th>
                        <th>Qty</th>
                        <th>Qty Order</th>
                        <th>Unit Price</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $p)
                    <tr style="margin-bottom: 10px;">
                        <td style="text-align: center;">{{ $p->code }}</td>
                        <td style="text-align: center;">{{ $p->name }}</td>
                        <td style="text-align: center;">{{ $p->size }}</td>
                        <td style="text-align: center;">{{ $p->qty }}</td>
                        <td style="text-align: center;">{{ $p->qty_order }}</td>
                        <td style="text-align: center;">{{ $p->price }}</td>
                        <td style="text-align: center;">{{ $p->sub_total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="detail-footer" style="margin-top: 10px">
            <div class="detail-right">

                @php
                    $total_price_sum = (clone $sales_order)->bzOrderItems()->sum('subtotal') * 0.35;
                @endphp

                <p class="">
                    <span class="question">Total Product Price </span>
                    <span class="answer">{{ $exchange_rate->from_currency . ' ' . number_format($total_price_sum / $exchange_rate->rate, 2, ',', '.') }}</span>
                </p>

                <p class="">
                    <span class="question">Transportation Fee </span>
                    <span class=" answer">{{ $exchange_rate->from_currency . ' ' . number_format($sales_order->shipping_total / $exchange_rate->rate, 2, ',', '.') }}</span>
                </p>

                <p class="bold"><span class="question">Grand Total </span>
                    <span class="bold answer">{{ $exchange_rate->from_currency . ' ' . number_format(($total_price_sum + $sales_order->shipping_total) / $exchange_rate->rate, 2, ',', '.')}}</span>
                </p>

                <hr style="4px solid black">
            </div>
        </div>

        <div class="employee-information">
            <p style="font-size: 12px">
                Note: {{ $sales_order->customer_note }}
            </p>
        </div>


        <hr style="1.5px solid black">


        <div class="employee-information">
            <div class="right">
                <p>
                    {{ $company_pt->id == 1 ? 'Tangerang, ' : '' }}
                    {{ Carbon::parse($sales_order->date_invoice_print)->format('d F Y') }}
                </p>
                <p style="padding-top: 50px;">
                    <a style="margin-right: 200px;">(</a>)
                </p>
            </div>
        </div>
    </div>


</body>

</html>