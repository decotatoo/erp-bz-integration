@extends('layouts.app')

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('sales-order.index') }}">Sales Order</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('sales-order.online.index') }}">Online List</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12">
            <form class="form" action="#" method="POST" id="voucher" onsubmit="return false">
                @method('put')
                @csrf
                <div class="box">
                    <div class="box-header with-border" style="    background: #1A233A;color: white;padding:15px">
                        <h4 class="box-title">UPDATE RELEASE SALES ORDER [ONLINE]</h4>

                        <div class="pull-right d-flex">
                            <div style="" class="" id="status_quotation">
                                <a class="btn  btn-info btn-sm " style="" id=""
                                    href="{{ route('sales-order.online') }}">Back</a>
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="box-body" style="">
                                <div style="border: solid 2px #DEE1E6;padding: 10px;border-radius: 19px;">
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="form-group @error('qo_number') error @enderror row">
                                                <label class="col-sm-4 col-form-label">SO Number <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control  "
                                                        value="{{ $sales_order->so_no }}" disabled>
                                                    <input type="hidden" id="product_id" value="">
                                                    @error('qo_number')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- product code --}}
                                            <div class="form-group @error('so_category') error @enderror row">
                                                <label class="col-sm-4 form-label">SO Category <span
                                                        class="text-danger">*</span></label>


                                                <div class="col-sm-8">
                                                    <select name="so_category" id="select" class="form-select" disabled>
                                                        <option value="">Select SO Category</option>
                                                        <option value="Reguler SO"
                                                            {{ (old('so_category') ?? optional($sales_order)->so_category) == 'Reguler SO' ? 'selected' : '' }}>
                                                            Reguler SO</option>
                                                        <option value="Reguler SO (Only Fee)"
                                                            {{ (old('so_category') ?? optional($sales_order)->so_category) == 'Reguler SO (Only Fee)' ? 'selected' : '' }}>
                                                            Reguler SO (Only Fee)</option>
                                                        <option value="Sample"
                                                            {{ (old('so_category') ?? optional($sales_order)->so_category) == 'Sample' ? 'selected' : '' }}>
                                                            Sample</option>
                                                        <option value="Sponsor"
                                                            {{ (old('so_category') ?? optional($sales_order)->so_category) == 'Sponsor' ? 'selected' : '' }}>
                                                            Sponsor</option>
                                                        <option value="Expired"
                                                            {{ (old('so_category') ?? optional($sales_order)->so_category) == 'Expired' ? 'selected' : '' }}>
                                                            Expired</option>
                                                        <option value="Spoilage"
                                                            {{ (old('so_category') ?? optional($sales_order)->so_category) == 'Spoilage' ? 'selected' : '' }}>
                                                            Spoilage</option>
                                                    </select>
                                                </div>

                                                @error('so_category')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            {{-- customer name --}}
                                            <div class="form-group @error('currency') error @enderror row">
                                                <label class="col-sm-4 col-form-label">Customer Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" placeholder="" name=""
                                                        id="customer_name"
                                                        value="{{ optional(optional($sales_order)->customer)->name }}"
                                                        readonly>
                                                </div>
                                                @error('currency')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group @error('currency') error @enderror row">
                                                <label class="col-sm-4 col-form-label">Customer Address</label>
                                                <div class="col-sm-8">
                                                    <textarea readonly class="form-control  " name="notes" id="" cols="30"
                                                        rows="3">{{ optional(optional($sales_order)->customer)->address }}</textarea>
                                                </div>
                                                @error('currency')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group @error('po_number') error @enderror row">
                                                <label class="col-sm-4 col-form-label">PO Number</label>
                                                <div class="col-sm-8">
                                                    <input readonly type="text" class="form-control  " placeholder=""
                                                        name="po_number"
                                                        value="{{ old('po_number') ?? $sales_order->po_number }}">
                                                </div>
                                                @error('po_number')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group @error('estimation_delivery_date') error @enderror row">
                                                <label class="col-sm-4 col-form-label">Estimation Delivery Date</label>
                                                <div class="col-sm-8">
                                                    <input readonly type="date" class="form-control  " placeholder=""
                                                        name="estimation_delivery_date"
                                                        value="{{ old('estimation_delivery_date') ?? $sales_order->estimation_delivery_date }}">
                                                </div>
                                                @error('estimation_delivery_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group @error('currency') error @enderror row">
                                                <label class="col-sm-4 col-form-label">Notes</label>
                                                <div class="col-sm-8">
                                                    <textarea readonly class="form-control  " name="notes" id="" cols="30"
                                                        rows="3">{{ optional($sales_order)->notes }}</textarea>
                                                </div>
                                                @error('currency')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>


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
                ajax: "{{ route('sales-order.online.listproduct') }}?id={{ $sales_order->id }}",
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
                ajax: "{{ route('sales-order.online.listscanout') }}?id={{ $sales_order->id }}",
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
                    console.log(row, data, dataIndex);
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
                let resp = await axios.post(`{{ route('sales-order.online.releaseproduct') }}`, {
                    so_no: '{{ $sales_order->so_no }}',
                    code: $('#barcode').val()
                });
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
                console.log('test');
                getScanOutDetails();
                getSalesOrderDetails();
            }
        }

        async function deleteLastItem(soNo, productId) {
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
                let resp = await axios.post("{{ route('sales-order.online.deletelastproduct') }}", {
                    productId,
                    soNo
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

    </style>

@endpush