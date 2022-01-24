@extends('layouts.app')

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('sales-order.index') }}">Sales Order</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('sales-order.online.base.index') }}">Online List</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="form" >
                <div class="box">
                    <div class="box-header with-border" style="    background: #1A233A;color: white;padding:15px">
                        <h4 class="box-title">DETAIL SALES ORDER [ONLINE]</h4>

                        <div class="pull-right d-flex">
                            <div style="" class="" id="status_quotation">
                                <a class="btn  btn-info btn-sm " style="" id=""
                                    href="{{ route('sales-order.online.base.index') }}">Back</a>
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="box-body" style="">
                                <div style="border: solid 2px #DEE1E6;padding: 10px;border-radius: 19px;">
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">
                                                    SO Number
                                                    @if (auth()->user()->can('sales-order-online-detail'))
                                                        <a target="_blank" href="{{ config('bz.base_url') . config('bz.dashboard_path') . '/post.php?action=edit&post=' . $sales_order->wp_order_id }}">🔗</a>
                                                    @endif
                                                </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" value="{{ $sales_order->uid }}" readonly>
                                                </div>
                                            </div>

                                            {{-- customer name --}}
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">
                                                    Customer Name
                                                    @if (auth()->user()->can('sales-order-online-detail'))
                                                        <a target="_blank" href="{{ config('bz.base_url') . config('bz.dashboard_path') . '/edit.php?post_status=all&post_type=shop_order&_customer_user=' . $sales_order->bzCustomer->wp_customer_id }}">🔗</a>
                                                    @endif
                                                </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control"
                                                        value="{{ $sales_order->bzCustomer->full_name }}"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">Billing</label>
                                                <div class="col-sm-8">
                                                    Name
                                                    <input type="text" class="form-control"
                                                        value="{{ $sales_order->billing['first_name'] }} {{ $sales_order->billing['last_name'] }}"
                                                        readonly>
                                                    Address
                                                    <textarea readonly class="form-control" cols="30" rows="3">{{ $sales_order->billing['address_1'] }} {{ $sales_order->billing['address_2'] ?? '' }}, {{ $sales_order->billing['city'] }}, {{ $sales_order->billing['state'] }}, {{ $sales_order->billing['country'] }}, {{ $sales_order->billing['postcode'] }}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">Shipping</label>
                                                <div class="col-sm-8">
                                                    Name
                                                    <input type="text" class="form-control" value="{{ $sales_order->shipping['first_name'] }} {{ $sales_order->shipping['last_name'] }}" readonly>
                                                    Address
                                                    <textarea readonly class="form-control" cols="30" rows="3">{{ $sales_order->shipping['address_1'] }} {{ $sales_order->shipping['address_2'] ? ', '. $sales_order->shipping['address_2'] : '' }}, {{ $sales_order->shipping['city'] }}, {{ $sales_order->shipping['state'] }}, {{ $sales_order->shipping['country'] }}, {{ $sales_order->shipping['postcode'] }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">Status</label>
                                                <div class="col-sm-8">
                                                    @php
                                                        $status_bg = '';
                                                        if ($sales_order->status == 'processing') {
                                                            $status_bg = '#ffc107';
                                                        } elseif ($sales_order->status == 'completed') {
                                                            $status_bg = '#28a745';
                                                        } elseif ($sales_order->status == 'canceled' || $sales_order->status == 'refunded') {
                                                            $status_bg = '#dc3545';
                                                        } 
                                                    @endphp

                                                    <input readonly type="text" class="form-control" value="{{$sales_order->status}}" style="background-color: {{ $status_bg }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">Creation Date</label>
                                                <div class="col-sm-8">
                                                    <input readonly type="date" class="form-control" name="date_created"
                                                        value="{{ \Illuminate\Support\Carbon::parse($sales_order->date_created)->toDateString() }}">
                                                </div>
                                            </div>

                                            @if ($sales_order->date_released)
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Released Date</label>
                                                    <div class="col-sm-8">
                                                        <input readonly type="date" class="form-control" name="date_released"
                                                            value="{{ \Illuminate\Support\Carbon::parse($sales_order->date_released)->toDateString() }}">
                                                    </div>
                                                </div>
                                            @endif


                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">Notes</label>
                                                <div class="col-sm-8">
                                                    <textarea readonly class="form-control" cols="30" rows="3">{{ optional($sales_order)->customer_note }}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">Transportation (Order)</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" placeholder="" value="{{ $sales_order->shipping_lines[0]['method_title'] }}" readonly>
                                                </div>
                                            </div>

                                            @if ($sales_order->date_released)
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Transportation (Actual)</label>
                                                    <div class="col-sm-8">
                                                        <div style="display: inline-flex;width: 100%;">
                                                            <select readonly disabled class="form-select" name="shipment_provider" id="shipment_provider" title="Provider" style="width: 50%;">
                                                                @foreach ($shipments as $shipment)
                                                                    <option value="{{ $shipment['id'] }}"
                                                                        {{ $sales_order->shipment_provider == $shipment['id'] ? 'selected' : '' }}>
                                                                        {{ $shipment['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <input type="date" readonly class="form-control" placeholder="Date Shipped" name="date_shipment_shipped" id="date_shipment_shipped" value="{{ $sales_order->date_shipment_shipped ? \Illuminate\Support\Carbon::parse($sales_order->date_shipment_shipped)->format('Y-m-d') : date('Y-m-d') }}" style="width: 45%;margin-left: 5px;">
                                                        </div>
                                                        <div style="display: inline-flex;width: 100%;padding-top: 5px;">
                                                            <input type="text" readonly name="shipment_tracking_number" id="shipment_tracking_number" value="{{ $sales_order->shipment_tracking_number }}" placeholder="AWB/Tracking number" class="form-control" style="width: 70%;">
                                                        </div>   
                                                    </div>
                                                </div>
                                                
                                                @if (auth()->user()->can('sales-order-online-invoice') && $sales_order->date_released)
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Invoice & Delivery Order</label>
                                                    <div class="col-sm-8" style="align-self: center;">
                                                        <a href="#print-invoice">
                                                            <i class="fas fa-file-invoice"></i>
                                                            Print Invoice and Delivery Order
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                            @endif

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label"></label>
                                                <div class="col-sm-8">
                                                                                                    
                                                    <div style="padding-top: 5px;">
                                                        @if ($sales_order->packingSimulation)
                                                            <a target="_blank" href="{{ route('packing-management.packing-simulation.visualiser', ['packingSimulation' => $sales_order->packingSimulation->id ]) }}">                              
                                                                <i class="fas fa-boxes fa-lg"></i> Packing Simulation
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box-body">
                                <h4 style="color: #E94E02">Product Details</h4>
                                <div class="">
                                    <table class="table table-sm" id="productOrderDetails" style="width:100%">
                                        <thead style="font-weight: bold ;background: #4f4c4c;color: white;">
                                            <tr>
                                                <td>Product ID</td>
                                                <td>Product Name</td>
                                                <td>Size</td>
                                                <td>Qty / Box</td>
                                                <td>Qty Order</td>
                                                <td>Qty Release</td>
                                                <td>Qty In Stock</td>
                                                <td>Price / Box ({{ $sales_order->currency }})</td>
                                                <td>Sub Total ({{ $sales_order->currency }})</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="box-body">
    
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Currency & Rate</label>
                                    <div class="col-sm-8" style="display: flex;">
                                        <input type="text" class="form-control" placeholder="" value="1 IDR =" readonly style="width: 25%;"> 
                                        <input type="text" class="form-control" placeholder="" value="{{ $sales_order->getMetaData('_dwi_currency_rate')['rate'] }} {{ $sales_order->getMetaData('_dwi_currency_rate')['currency'] }}" readonly style="width: 74%;margin-left:1%;font-weight: 600">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Payment Method</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" placeholder="payment method" value="{{$sales_order->payment_method_title}}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Transaction ID</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" placeholder="trx id" value="{{$sales_order->transaction_id}}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Shipping Service</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" placeholder="" value="{{ $sales_order->shipping_lines[0]['method_title'] }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Shipping Cost</label>
                                    <div class="col-sm-8" style="display: flex;">
                                        <input type="text" class="form-control" placeholder="" value="{{ $sales_order->currency }} {{ $sales_order->shipping_lines[0]['total'] }}" readonly style="width: 50%;"> 
                                        @if ($sales_order->currency != 'IDR')
                                            @if ($sales_order->shipping_lines[0]['method_id'] == 'woongkir')
                                                <input type="text" class="form-control" placeholder="" value="{{ $sales_order->shipping_lines[0]['meta_data'][0]['value']['currency'] }} {{ $sales_order->shipping_lines[0]['meta_data'][0]['value']['cost'] }}" readonly style="width: 49%;margin-left:1%;">
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Total Discount</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" placeholder="" value="{{ $sales_order->currency }} {{ $sales_order->discount_total }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Is Tax (10%)</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" placeholder="" value="{{ $sales_order->getMetaData('is_vat_exempt') == 'no' ? 'yes' : 'no'  }} " readonly>
                                    </div>
                                </div>

                                @if ('no' == $sales_order->getMetaData('is_vat_exempt'))
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Total Tax</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" placeholder="" value="{{ $sales_order->currency }} {{ $sales_order->total_tax }}" readonly>
                                        </div>
                                    </div>
                                @endif


                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="box-body">
                                {{-- currency --}}
                                <h3>GRAND SUB TOTAL ({{$sales_order->currency}}) :</h3>
                                <div style="background:#E9ECEF;padding:30px;border-radius:19px;">
                                    <span style="font-size: 30px;font-family: monospace;font-weight: 100;text-decoration: line-through;"
                                        id="before_discount"></span>
                                    <span style="font-size: 30px; color:red;font-family:monospace;font-weight: 100;"
                                        id="discount_value"></span>
                                    <span style="display:block;font-size: 56px;font-family: monospace;font-weight: 700;"
                                        id="quotation_total_price">{{ number_format($sales_order->total, 2, '.', ',') }}</span>
                                </div>
                            </div>



                            @if (auth()->user()->can('sales-order-online-invoice') && $sales_order->date_released)
                            <a id="print-invoice" href="{{ route('sales-order.online.invoice.consumer.print', ['bzOrder' => $sales_order->id]) }}" target="blank" class="btn wd-100 btn-success" style="width: 100%"> 
                                Create Invoice & Delivery Order
                            </a>
                            @endif


                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>


    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') }}">
    </script>
    <script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('/') }}/assets/vendor_components/PACE/pace.min.js"></script>
    <script src="{{ asset('/') }}/assets/vendor_components/jquery-toast-plugin-master/src/jquery.toast.js"></script>
    <script src="{{ asset('/') }}/assets/vendor_components/sweetalert/sweetalert.min.js"></script>

    <script>
        // ----------------------- INITIAL PAGE
        $('body').addClass('sidebar-collapse');
        $('#main-content').removeClass('container');
        $('#main-content').addClass('container-fluid');
        getSalesOrderDetails();

        $('#productOrderDetails tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = $('#productOrderDetails').DataTable().row(tr);            
        });

        // --- QUOTATION DETAILS
        async function getSalesOrderDetails() {
            $.fn.dataTableExt.sErrMode = "console";
            $('#productOrderDetails').DataTable().destroy();

            $('#productOrderDetails').DataTable({
                processing: true,
                serverSide: true,
                ajax: route('sales-order.online.base.listproduct', {
                    bzOrder: {{ $sales_order->id }}
                }),
                columns: [{

                        data: 'product_id',
                        name: 'product_id',
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'size',
                        name: 'size'
                    },
                    {
                        data: 'qty_box',
                        name: 'qty_box'
                    },
                    {
                        data: 'qty_order',
                        name: 'qty_order'
                    },
                    {
                        data: 'qty_release',
                        name: 'qty_release'
                    },
                    {
                        data: 'qty_in_stock',
                        name: 'qty_in_stock',
                    },
                    {
                        data: 'price',
                        name: 'price',
                    },
                    {
                        data: 'price_subtotal',
                        name: 'price_subtotal',
                    },
                ]
            });
        }

    </script>
@endpush

@push('styles')
    <style>
        table.dataTable {
            clear: both;
            margin-top: 6px !important;
            margin-bottom: 6px !important;
            max-width: none !important;
            border-collapse: collapse !important;
            font-family: monospace;
        }

        table.dataTable td,
        table.dataTable th {
            -webkit-box-sizing: content-box;
            box-sizing: content-box;
            border: solid 0px #DEE2E6;

        }

        .table>tbody>tr>td,
        .table>tbody>tr>th {
            padding: 1rem;
            vertical-align: middle;
            font-size: 14px;
            font-weight: bold;
            padding: 1px 14px;
        }

        table.dataTable thead>tr>th.sorting_asc,
        table.dataTable thead>tr>th.sorting_desc,
        table.dataTable thead>tr>th.sorting,
        table.dataTable thead>tr>td.sorting_asc,
        table.dataTable thead>tr>td.sorting_desc,
        table.dataTable thead>tr>td.sorting {
            padding: 8px 14px;

            border: 0.5px solid #DEE2E6;
            border-collapse: collapse;
        }

        .table-detail {
            border-collapse: collapse !important;
            border: 1px #323539 solid !important;
        }

        .table-detail>tbody>tr>td,
        .table>tbody>tr>th {
            border: 1px #323539 solid !important;
        }

        
        .dataTable tbody .details-control:before {
            margin-right: 10px;
            content: "\f05a";
            color: #38c1f1;
        }

        .dataTable tbody .shown .details-control::before {
            content: "\f05a";
            color: #7e6e71;
        }

    </style>

@endpush