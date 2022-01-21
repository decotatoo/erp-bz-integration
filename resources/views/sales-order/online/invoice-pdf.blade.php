<!DOCTYPE html>
<html>
<head>
	<title>Invoice</title>
    <style type="text/css">
        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed,
        figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
            font-family: sans-serif;
        }
        /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure,
        footer, header, hgroup, menu, nav, section {
            display: block;
        }
        body {
            line-height: 1;
        }
        ol, ul {
            list-style: none;
        }
        blockquote, q {
            quotes: none;
        }
        blockquote:before, blockquote:after,
        q:before, q:after {
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
            padding:15px;
        }

        .page-break {
            page-break-after: always;
        }

        .left-signature{
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
                    {{ $company->name }}
                </p>
                <p>
                    {{ $company->address }}
                </p>
                <p>
                    @if($company->phone)
                        Tel. {{ $company->phone }}
                    @endif
                    @if ($company->email)
                        Email: {{ $company->email }}
                    @endif
                </p>
            </div>

            <div class="right">
                <p class="">Invoice Number: <span style="float: right;">{{ $sales_order->so_no }}</span></p>
                <p class="">Invoice Date: <span style="float: right;">{{ date('d F Y', strtotime($sales_order->estimation_delivery_date)) }}</span></p>
                <p class="">Term Of Payment: <span style="float: right;">{{ $sales_order->term_of_payments ?? $sales_order->customer->term_of_payments }}</span></p>
            </div>
        </div>

        <hr style="border: 1.1px solid black;">

        <div class="employee-information">
            <div class="left">
                <p class="bold">Bill To</p>
                <p>
                    {{ $sales_order->customer->name }}
                    {{ $sales_order->customer->bill }}
                </p>
                <p class="bold">Po No: {{ $sales_order->po_number }}</p>
            </div>

            <div class="right">
                <p class="bold">Delivery To</p>
                <p>
                    {{ $sales_order->customer->delivery_address }}
                </p>
                <p>Phone : {{ $sales_order->customer->phone }}</p>
                <p>Email : {{ $sales_order->customer->email1 }}</p>
                <p class="bold">Delivery Date: {{ date('d F Y', strtotime($sales_order->estimation_delivery_date)) }}</p>
            </div>
        </div>

        <hr style="border: 1.1px solid black;">

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Product code</th>
                        <th>Size</th>
                        <th>Product description</th>
                        <th>Qty</th>
                        <th>Qty Order</th>
                        <th>Unit Price</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                {{-- <hr style="border: 1.1px solid black;"> --}}
                <tbody>
                    @foreach ($products as $p)
                    <tr style="margin-bottom: 10px;">
                        <td style="text-align: center;">{{ $p->code }}</td>
                        <td style="text-align: center;">{{ $p->category_prod . " " . $p->name }}</td>
                        <td style="text-align: center;">{{ $p->size }}</td>
                        <td style="text-align: center;">{{ $p->qty }}</td>
                        <td style="text-align: center;">{{ $p->qty_order }}</td>
                        <td style="text-align: center;">{{ $p->currency . " " . $p->unit_price }}</td>
                        <td style="text-align: center;">{{ $p->currency . " " . $p->sub_total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="detail-footer" style="margin-top: 10px">
            <div class="detail-right">
                @if ($sales_order->customer->company_id == 1)
                <p class=""><span class="question">Total Product Price </span> <span class="answer">{{ $currency . " " . $total_product_price}}</span></p>
                <p class=""><span class="question">Discount {{ $discount_count }} </span> <span class="answer">{{ $currency . " -" . $discount}}</span></p>
                <p class="bold"><span class="question">Total Net Value </span> <span class="bold answer">{{ $currency . " " . $net_value}}</span></p>
                <p class=""><span class="question">PPN 10% </span> <span class=" answer">{{ $currency . " " . $tax}}</span></p>
                @isset ($transportation_fee)
                <p class=""><span class="question">Transportation Fee </span> <span class=" answer">{{ $currency . " " . $transportation_fee}}</span></p>
                @endisset

                @isset ($artwork_fee)
                <p class=""><span class="question">Artwork Fee </span> <span class=" answer">{{ $currency . " " . $artwork_fee}}</span></p>
                @endisset

                @isset ($tool_fee)
                <p class=""><span class="question">Tool Fee </span> <span class=" answer">{{ $currency . " " . $tool_fee}}</span></p>
                @endisset

                <p class="bold"><span class="question">Grand Total </span> <span class="bold answer">{{ $currency . " " . $grand_total}}</span></p>
                @else
                <p class=""><span class="question">{{ $title_total_product_price }} </span> <span class="answer">{{ $currency . " " . $total_product_price}}</span></p>
                <p class=""><span class="question">Discount {{ $discount_count }} </span> <span class="answer">{{ $currency . " -" . $discount}}</span></p>
                <p class="bold"><span class="question">Total Net Value </span> <span class="bold answer">{{ $currency . " " . $net_value}}</span></p>

                @isset ($transportation_fee)
                <p class=""><span class="question">Transportation Fee </span> <span class=" answer">{{ $currency . " " . $transportation_fee}}</span></p>
                @endisset

                @isset ($artwork_fee)
                <p class=""><span class="question">Artwork Fee </span> <span class=" answer">{{ $currency . " " . $artwork_fee}}</span></p>
                @endisset

                @isset ($tool_fee)
                <p class=""><span class="question">Tool Fee </span> <span class=" answer">{{ $currency . " " . $tool_fee}}</span></p>
                @endisset

                <p class="bold"><span class="question">Grand Total </span> <span class="bold answer">{{ $currency . " " . $grand_total}}</span></p>
                @endif

                <hr style="4px solid black">
            </div>
        </div>

        <div class="employee-information">
            <p style="font-size: 12px">
                Note: {{ $sales_order->notes }}
            </p>
        </div>


        <hr style="1.5px solid black">
        <div class="employee-information">
            @if ($sales_order->customer->company_id == 1)
            <div class="left">
                <p class="bold">
                    Bank Transfer
                </p>
                <p style="margin-left:15px;">
                   Account Name: PT. Deco Kreasindo
                </p>
                <p style="margin-left:15px;">
                    Bank Name : Bank Mandiri
                </p>
                <p style="margin-left:15px;">
                    Account Number : 164-00-0800899-9
                </p>
            </div>
            @else
            <div class="left">
                <p class="bold">
                    Bank Transfer
                </p>
                <p style="margin-left:15px;">
                    Account Name : DECOTATOO,CO.,LTD
                </p>
                <p style="margin-left:15px;">
                    Bank Name : HSBC Hong Kong
                </p>
                <p style="margin-left:15px;">
                    Branch Address : 1 Queen's Road Central, Hong Kong
                </p>
                <p style="margin-left:15px;">
                    Account Number : 582 276135 838
                </p>
                <p style="margin-left:15px;">
                    Swift Code : HSBCHKHHHKH
                </p>
            </div>
            @endif
        </div>

        <hr style="1.5px solid black">
        <div class="employee-information">
            <div class="right">
                @if ($sales_order->customer->company_id == 1)
                <p>Tangerang, {{ date('d F Y', strtotime($sales_order->estimation_delivery_date)) }}</p>
                <p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
                @else
                <p>     {{ date('d F Y', strtotime($sales_order->estimation_delivery_date)) }}</p>
                <p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
                @endif
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="content">
        <div style="text-align: center; margin-top: 10px">
            <img src="{{ asset('images/logo.png') }}" alt="" class="">
        </div>
        <div style="text-align: center; margin-top: 10px">
            <p class="bold">Delivery Order</p>
        </div>


        <div class="employee-information">
            <div class="left">
                <p class="bold">
                    {{ $company->name }}
                </p>
                <p>
                    {{ $company->address }}
                </p>
                <p>
                    @if($company->phone)
                        Tel. {{ $company->phone }}
                    @endif
                    @if ($company->email)
                        Email: {{ $company->email }}
                    @endif
                </p>
            </div>

            <div class="right">
                <p class="">Invoice Number: <span style="float: left;">{{ $sales_order->so_no }}</span></p>
                <p class="">Invoice Date: <span style="float: left;">{{ date('d F Y', strtotime($sales_order->estimation_delivery_date)) }}</span></p>
                <p class="">Term Of Payment: <span style="float: left;">{{ $sales_order->term_of_payments ?? $sales_order->customer->term_of_payments }}</span></p>
           </div>
        </div>

        <hr style="border: 1.1px solid black;">

        <div class="employee-information">
            <div class="left">
                <p class="bold">Bill To</p>
                <p>
                    {{ $sales_order->customer->name }}
                    {{ $sales_order->customer->bill }}
                </p>
                <p class="bold">Po No: {{ $sales_order->po_number }}</p>
            </div>

            <div class="right">
                <p class="bold">Delivery To</p>
                <p>
                    {{ $sales_order->customer->delivery_address }}
                </p>
                <p>Phone : {{ $sales_order->customer->phone }}</p>
                <p>Email : {{ $sales_order->customer->email1 }}</p>
                <p class="bold">Delivery Date: {{ date('d F Y', strtotime($sales_order->estimation_delivery_date)) }}</p>
            </div>
        </div>

        <hr style="border: 1.1px solid black;">

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Product code</th>
                        <th>Size</th>
                        <th>Product description</th>
                        <th>Qty</th>
                        <th>Qty Order</th>
                        <th>Qty Receive</th>
                    </tr>
                </thead>
                {{-- <hr style="border: 1.1px solid black;"> --}}
                <tbody>
                    @foreach ($do_products as $p)
                    <tr style="margin-bottom: 10px;">
                        <td style="margin-right: 5px;">{{ $p->code }}</td>
                        <td style="text-align: center;">{{ $p->size }} </td>
                        <td style="text-align: center;">{{ $p->category_prod . " " . $p->name }}</td>
                        <td style="text-align: center;">{{ $p->qty }}</td>
                        <td style="text-align: center;">{{ $p->qty_order }}</td>
                        <td style="text-align: center;">.....................</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="employee-information">
            <div class="left-signature">
                <p style="text-align:center;">SENT BY</p>
                <p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
            </div>
            <div class="right">
                <p style="text-align:center;">RECEIVED BY</p>
                <p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
            </div>
        </div>
    </div>

</body>
</html>
