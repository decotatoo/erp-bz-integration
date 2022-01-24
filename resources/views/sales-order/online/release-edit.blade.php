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
            <form class="form" action="#" method="POST" id="" onsubmit="return false">
                @method('put')
                @csrf
                <div class="box">
                    <div class="box-header with-border" style="    background: #1A233A;color: white;padding:15px">
                        <h4 class="box-title">UPDATE RELEASE SALES ORDER [ONLINE]</h4>

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
                                                    <input type="text" class="form-control" value="{{ $sales_order->uid }}" disabled>
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
                                                <label class="col-sm-4 col-form-label">Creation Date</label>
                                                <div class="col-sm-8">
                                                    <input readonly type="date" class="form-control" name="date_created"
                                                        value="{{ \Illuminate\Support\Carbon::parse($sales_order->date_created)->toDateString() }}">
                                                </div>
                                                @error('date_created')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            @if ($sales_order->date_released)
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Released Date</label>
                                                    <div class="col-sm-8">
                                                        <input readonly type="date" class="form-control" name="date_released"
                                                            value="{{ \Illuminate\Support\Carbon::parse($sales_order->date_released)->toDateString() }}">
                                                    </div>
                                                    @error('date_released')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif


                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">Notes</label>
                                                <div class="col-sm-8">
                                                    <textarea readonly class="form-control" cols="30" rows="3">{{ optional($sales_order)->customer_note }}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">Shipping</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" placeholder="" value="{{ $sales_order->shipping_lines[0]['method_title'] }}" readonly>
                                                </div>
                                            </div>

                                            

                                            @if ($sales_order->date_released)
                                                <div class="form-group @error('shipment') error @enderror row">
                                                    <label class="col-sm-4 col-form-label">Delivery</label>
                                                    <div class="col-sm-8">
                                                        <div style="display: inline-flex;width: 100%;">
                                                            <select class="form-select" name="shipment_provider" id="shipment_provider" title="Provider" style="width: 50%;">
                                                                @foreach ($shipments as $shipment)
                                                                    <option value="{{ $shipment['id'] }}"
                                                                        {{ $sales_order->shipment_provider == $shipment['id'] ? 'selected' : '' }}>
                                                                        {{ $shipment['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <input type="date" class="form-control" placeholder="Date Shipped" name="date_shipment_shipped" id="date_shipment_shipped" value="{{ $sales_order->date_shipment_shipped ? \Illuminate\Support\Carbon::parse($sales_order->date_shipment_shipped)->format('Y-m-d') : date('Y-m-d') }}" style="width: 45%;margin-left: 5px;">
                                                        </div>
                                                        <div style="display: inline-flex;width: 100%;padding-top: 5px;">
                                                            <input type="text" name="shipment_tracking_number" id="shipment_tracking_number" value="{{ $sales_order->shipment_tracking_number }}" placeholder="AWB/Tracking number" class="form-control" style="width: 70%;">
                                                            <button class="btn btn-success btn-sm" style="width: 20%;margin-left: 5px;" onclick="updateShipment(event)" {{ ($sales_order->date_shipment_shipped && \Illuminate\Support\Carbon::parse($sales_order->date_shipment_shipped)->addDays(3)->lessThan(\Illuminate\Support\Carbon::now())) || \Illuminate\Support\Carbon::parse($sales_order->date_released)->addDays(7)->lessThan(\Illuminate\Support\Carbon::now()) ? 'disabled' : '' }}>
                                                                Update
                                                            </button>
                                                        </div>                                                  
                                                    </div>
                                                    @error('shipment')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                
                                                @if (auth()->user()->can('sales-order-online-invoice'))
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Invoice & Delivery Order</label>
                                                    <div class="col-sm-8" style="align-self: center;">
                                                        <a href="{{ route('sales-order.online.invoice.consumer.print', ['bzOrder' => $sales_order->id]) }}" target="_blank">
                                                            <i class="fas fa-file-invoice"></i>
                                                            Print Invoice and Delivery Order
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                            @else
                                                <div class="form-group @error('barcode') error @enderror row">
                                                    <label class="col-sm-4 col-form-label">Stock Out Product</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" placeholder="Scan Barcode Here"
                                                            name="barcode" value="" id="barcode"
                                                            onkeypress="releaseProduct(event)">
                                                    </div>
                                                    @error('barcode')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
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
                                <h4 style="color: #E94E02">Scan Out Details</h4>
                                <div class="">
                                    <table class="table table-sm" id="scanOutDetails" style="width:100%">
                                        <thead style="font-weight: bold ;background: #4f4c4c;color: white;">
                                            <tr>
                                                <td>Product ID</td>
                                                <td>Product Name</td>
                                                <td>Size</td>
                                                <td>Qty / Box</td>
                                                <td>Qty Order</td>
                                                <td>Qty Release</td>
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
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            </form>
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
        getScanOutDetails();
        getSalesOrderDetails();

        function format(rowData) {
            rowData = rowData.stock_detail;
            var childTable =
                `<div style="background:#ebebeb;padding:20px">
            <table class="table table-detail  table-sm" style="border-collapse:collapse !important;">
                <thead class="table-dark" style="background: #323539;">
                    <tr>
                        <th>Production Date</th>
                        <th>Manufacture Date</th>
                        <th>Expired Date</th>
                        <th>Stock In</th>
                        <th>Stock Out</th>
                        <th>Stock Available</th>
                    </tr>
                </thead>
                <tbody>`

            rowData.forEach(element => {
                childTable += `
                     <tr>
                        <td>${element.production_date}</td>
                        <td>${element.manufacture_date}</td>
                        <td>${element.expired_date}</td>
                        <td>${element.stock_in}</td>
                        <td>${element.stock_out}</td>
                        <td>${element.stock_available}</td>
                    </tr> `;
            });


            childTable += `
                </tbody>
        </table></div>`;
            return childTable;
        }

        $('#productOrderDetails tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = $('#productOrderDetails').DataTable().row(tr);
            // console.log(JSON.parse(row.data().order_details));

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
                $('.table-detail').DataTable();

            }
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
                        className: 'details-control',
                        orderable: false,
                        defaultContent: '',
                        data: 'qty_in_stock',
                    },
                    {
                        data: 'btnAction',
                        name: 'btnAction'
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    // $(row).css('cursor', 'pointer');
                    // $('#ButtonName').attr('onClick', 'FunctionName(this);');
                    // $('#save_quotation').removeAttr('disabled');
                }
            });
        }

        // --- QUOTATION DETAILS
        async function getScanOutDetails() {
            $.fn.dataTableExt.sErrMode = "console";
            $('#scanOutDetails').DataTable().destroy();
            $('#scanOutDetails').DataTable({
                processing: true,
                serverSide: true,
                ajax: route('sales-order.online.base.listscanout', {
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
                ],
                createdRow: function(row, data, dataIndex) {
                    if (data.qty_release == 0) {
                        this.api().row(row).remove() //<-----
                    }

                    if (data.qty_release == data.qty_order) {
                        $(row).addClass('bg-success')
                    } else if (data.qty_release < data.qty_order) {
                        $(row).addClass('bg-warning')

                    }
                    // $(row).css('cursor', 'pointer');
                    // $('#ButtonName').attr('onClick', 'FunctionName(this);');
                    // $('#save_quotation').removeAttr('disabled');
                }
            });
        }

        async function releaseProduct(e) {
            if (e.key == "Enter") {
                let resp = await axios.post(
                    route('sales-order.online.base.releaseproduct', { 
                        bzOrder: {{ $sales_order->id }} 
                    }), 
                    {
                        code: $('#barcode').val()
                    }
                );
                resp = resp.data;
                $('#barcode').val('')
                console.log(resp);
                if (resp.status != 200) {
                    $.toast({
                        heading: 'Failed',
                        text: resp.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'warning',
                        hideAfter: 3000,
                        stack: 6
                    });
                    return false;
                }

                try {
                    $.toast({
                        heading: 'Success',
                        text: resp.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 6
                    });

                    

                    if (resp.is_released == true) {
                        console.log('is_released');
                        $.toast({
                            heading: 'Refreshing the page !',
                            text: resp.message,
                            position: 'top-right',
                            loaderBg: '#4970ff',
                            icon: 'info',
                            hideAfter: 8000,
                            stack: 6
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 8000);
                    } else {
                        console.log('not_released');
                    }


                } catch (error) {
                    $.toast({
                        heading: 'Failed',
                        text: resp.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'warning',
                        hideAfter: 3000,
                        stack: 6
                    });
                }
                getScanOutDetails();
                getSalesOrderDetails();
            }
        }

        async function updateShipment(e) {
            provider = document.getElementById('shipment_provider').value;
            tracking_numner = document.getElementById('shipment_tracking_number').value;
            date_shipment_shipped = document.getElementById('date_shipment_shipped').value;

            if (tracking_numner == '') {
                $.toast({
                    heading: 'Failed',
                    text: 'Please enter tracking number',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'warning',
                    hideAfter: 3000,
                    stack: 6
                });
                return false;
            }

            let resp = await axios.post(
                route('sales-order.online.base.updateshipment', { 
                    bzOrder: {{ $sales_order->id }} 
                }), 
                {
                    provider: provider,
                    tracking_number: tracking_numner,
                    date_shipment_shipped: date_shipment_shipped
                }
            );

            resp = resp.data;

            if (resp.status === 200) {
                $.toast({
                    heading: 'Success !',
                    text: resp.message,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3000,
                    stack: 6
                });
            } else {
                $.toast({
                    heading: 'Failed !',
                    text: resp.message,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'danger',
                    hideAfter: 3000,
                    stack: 6
                });
            }
        }

        async function deleteLastItem(id) {
            swal({
                title: "Confirmation !",
                text: "are you sure delete last scan out this product? ",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#16825D",
                confirmButtonText: "Yes, Delete it !",
                closeOnConfirm: false
            }, async function() {
                swal.close();
                let resp = await axios.post(route('sales-order.online.base.deletelastproduct', { 
                        bzOrder: {{ $sales_order->id }} 
                    }), {
                    id,
                });
                resp = resp.data;
                if (resp.status === 200) {
                    $.toast({
                        heading: 'Success !',
                        text: resp.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 6
                    });

                    getScanOutDetails();
                    getSalesOrderDetails();


                } else {
                    $.toast({
                        heading: 'Failed !',
                        text: resp.message,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'danger',
                        hideAfter: 3000,
                        stack: 6
                    });

                    getScanOutDetails();
                    getSalesOrderDetails();

                }

            })

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