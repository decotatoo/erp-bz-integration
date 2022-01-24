<!DOCTYPE html>
<html>

@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;
@endphp

<head>
    <title>Report {{ $sales_order->uid }}</title>
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

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

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
            width: 40%;
        }

        .right {
            float: right;
            width: 50%;
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

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="content">
        <div style="text-align: center; margin-top: 10px">
            <img src="{{ asset('images/logo.png') }}" alt="" class="">
        </div>
        <div style="text-align: center; margin-top: 10px">
            <p class="bold">SALES ORDER</p>
        </div>


        <div class="employee-information">
            <div class="left">
                <p class="">SO Number: {{ $sales_order->uid }}</span></p>
                <p class="">Creation Date: {{ Carbon::parse($sales_order->date_created)->format('Y-m-d') }}</span></p>
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($sales_order->uid, 'C93',1.3, 40) }}" alt="barcode" />
            </div>

            <div class="right">
                <p>
                    {!! Str::of($company->address)->replaceMatches('/\n/', '<br>') !!}
                    <br>
                    @if ($company->phone)
                        Telp. {{ $company->phone }}
                    @endif
                    <br>
                    @if ($company->email)
                        Email: {{ $company->email }}
                    @endif
                </p>
            </div>
        </div>

        <hr style="border: 1.1px solid black;">

        <div class="employee-information">
            <div class="left">
                <p class="bold">Billing</p>
                <p>
                    {{ $sales_order->billing['first_name'] ?? '' }} {{ $sales_order->billing['last_name'] ?? '' }}
                </p>
                <p class="">
                    {{ $sales_order->billing['address_1'] }},
                    {{ $sales_order->billing['address_2'] ? $sales_order->billing['address_2'] . ', ' : ''}}
                    {{ $sales_order->billing['city'] }}, 
                    {{ $sales_order->billing['state'] }}, 
                    {{ $sales_order->billing['country'] }} -  
                    {{ $sales_order->billing['postcode'] }} 
                </p>
                <p>
                    @if ($sales_order->billing['phone'])
                        Phone : {{ $sales_order->billing['phone'] }}
                    @endif
                </p>
                <p>
                    @if ($sales_order->billing['email'])
                        Email : {{ $sales_order->billing['email'] }}
                    @endif
                </p>
            </div>


            <div class="right">
                <p class="bold">Shipping</p>
                <p>
                    {{ $sales_order->shipping['first_name'] ?? '' }} {{ $sales_order->shipping['last_name'] ?? '' }}
                </p>
                <p class="">
                    {{ $sales_order->shipping['address_1'] }},
                    {{ $sales_order->shipping['address_2'] ? $sales_order->shipping['address_2'] . ', ' : ''}}
                    {{ $sales_order->shipping['city'] }}, 
                    {{ $sales_order->shipping['state'] }}, 
                    {{ $sales_order->shipping['country'] }} -  
                    {{ $sales_order->shipping['postcode'] }} 
                </p>
                <p>
                    @if ($sales_order->shipping['phone'])
                        Phone : {{ $sales_order->shipping['phone'] }}
                    @endif
                </p>
            </div>

        </div>

        <hr style="border: 1.1px solid black;">

        <div class="table">
            <table>
                <tr>
                    <th>Product description</th>
                    <th>Size</th>
                    <th>Qty / Box</th>
                    <th>Qty Order</th>
                </tr>
                @foreach ($products as $p)
                <tr style="margin-bottom: 10px">
                    <td style="margin-right: 5px">{{ '(' . $p->code . ') ' . $p->name }}</td>
                    <td>{{ $p->size }}</td>
                    <td>{{ $p->qty_box }}</td>
                    <td>{{ $p->qty_order }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="employee-information">
            <p>
                Note: {{ $sales_order->customer_note }}
            </p>
        </div>
</body>

</html>