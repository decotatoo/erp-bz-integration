@extends('layouts.app')

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('sales-order.index') }}">Sales Order</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('sales-order.online.base.index') }}">Online</a></li>
<li class="breadcrumb-item active" aria-current="page">Report</li>
@endsection

@section('content')

<div class="col-12">

    <div class="d-flex flex-row justify-content-between">
        <h4 class="box-title align-items-start flex-column">
            Sales Order [ONLINE] - <span style="text-decoration-line: underline;"> Report </span>
            <small class="subtitle">Report of sales order online</small>
        </h4>
    </div>

    <div class="bg-info-light px-20 py-10 rounded mt-10">
        <div class="d-lg-flex justify-content-between align-items-center">
            <div class="col-12">
                <div class="d-flex flex-row justify-content-between">
                    <div class="align-items-start">
                        <div class="text-dark text-bold">
                            {{-- Note --}}
                        </div>
                        <div class="mt-5">
                        </div>
                        <div class="mt-0">
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="form-group" style="margin-right: 5px">
                            <label for="start_date" class="form-label">Start Date</label>
                            
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ Request::get('start_date') ?? date('Y-m-01') }}" placeholder="Start Date">
                        </div>
    
                        <div class="form-group" style="margin-right: 5px">
                            <label for="end_date" class="form-label">End Date</label>
                            
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ Request::get('end_date') ?? date('Y-m-t') }}" placeholder="Start Date">
                        </div>

                        <div class="form-group" style="margin-right: 5px">
                            <label for="date_type" class="form-label">Date based on</label>
                            <select name="date_type" id="date_type" class="form-select">
                                <option value="date_created">Creation</option>
                                <option value="date_paid">Payment date</option>
                                <option value="date_completed">Order Completed</option>
                                <option value="date_released">Order Released</option>
                                <option value="date_shipment_shipped">Order Shipped</option>
                                {{-- <option value="date_shipment_delivered">Order Delivered</option> --}}
                            </select>
                        </div>
                    </div>
    
                    <div class="text-end mt-20">
                        @if (auth()->user()->can('journal-create'))
                            <a href="#" class="btn btn-warning btn-rounded" onclick="showFilterdData()"><i class="fa fa-eye"></i> Show</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-12 mt-5">
            @include('components.flash-message')
        </div>  
    </div>   
    
    <div class="box mt-10">
        <div class="box-body">
            <div class="table-responsive">
                <form action="" method="POST" id="printForm" target="_blank">
                    @csrf

                    <table id="salesOrderTable" class="table no-border table-sm" style="font-size: 12px; ">
                        <thead>
                            <tr class="text-uppercase bg-lightest">
                                <th style="min-width: 1px"><span class="text-dark">No</span></th>
                                <th style="min-width: 25px"><span class="text-dark">SO Number</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Customer Name</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Order Date</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Delivery Date</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Items</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Product Value</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Tax</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Shipping Cost</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Notes</span></th>
                                <th style="min-width: 80px" class="text-center"><span class="text-dark">Action</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>










    <div class="bg-info p-20 rounded mt-30" style="background-color: #17586f !important">
        <div class="d-lg-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="me-15 bg-white h-40 w-40 l-h-50 rounded text-center">
                    <h2 class="fs-18 text-info" style="color: #17586f !important;">Rp</h2>
                </div>
                <div class="d-flex flex-column fw-500">
                    <a href="#" class="text-white hover-success fs-16" id="revenue_idr">0,00</a>
                    <span class="text-white-50">Total Revenue (IDR)</span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="me-15 bg-white h-40 w-40 l-h-50 rounded text-center">
                    <h2 class="fs-18 text-info" style="color: #17586f !important;">HK$</h2>
                </div>
                <div class="d-flex flex-column fw-500">
                    <a href="#" class="text-white hover-danger fs-16" id="revenue_hkd">0,00</a>
                    <span class="text-white-50">Total Revenue (HKD)</span>
                </div>
            </div>
        </div>
    </div>















































</div>
@endsection

@push('scripts')
	<script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>
	
    <script>
        const salesOrderTable = $('#salesOrderTable').DataTable();

        function fixDate(formDate) {
            const date = new Date(formDate);
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();
            return {
                string: [day, month, year].join('/'),
                date: date,
                formDate: formDate
            }
        }

        function showFilterdData() {
            startDate = fixDate($('#start_date').val());
            endDate = fixDate($('#end_date').val());
            date_type = $('#date_type').val();
            order_status = $('#order_status').val();

            salesOrderTable.clear().draw();

            if (!$('#start_date').val() || !$('#end_date').val() || (startDate.date - endDate.date > 0)) {
                $.confirm({
                    title: 'Error Period Date!',
                    content: 'Please check start date or end date',
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        close: function () {
                        }
                    }
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('sales-order.online.base.list') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                        startDate: startDate.formDate,
                        endDate: endDate.formDate,
                        order_status,
                        date_type,
                    },
                    success: function (data) {
                        number = 1;
                        data.data.salesOrders.forEach((value, index) => {
                            let detailProductOrder = `
                                <a href="#" data-toggle="tooltip" data-placement="top" title="Detail product order" class="btn btn-sm btn-primary btn-rounded" onclick="detailModal('Detail Product Order', '${route('sales-order.online.base.detail-product', { bzOrder: value.id, view_type: 'report' } )}', 'x-large')"><i class="fa fa-eye"></i> Detail</a>
                            `;


                            let action = `
                                <a href="${route('sales-order.online.base.detail-release', { bzOrder: value.id})}" class="btn btn-sm btn-info btn-rounded" target="blank"><i class="fa fa-print"></i> Print</a>
                            `;

                            let tr = $(`
                                <tr>
                                    <td>${number}</td>
                                    <td>${value.so_no}</td>
                                    <td>${value.customer_name}</td>
                                    <td>${value.date_order}</td>
                                    <td>${value.date_shipped ?? ''}</td>
                                    <td>${detailProductOrder}</td>
                                    <td>${value.currency + ' ' + value.total_order_value}</td>
                                    <td>${value.total_tax ? value.currency + ' ' + value.total_tax : ''}</td>
                                    <td>${value.shipping_total ? value.currency + ' ' + value.shipping_total : ''}</td>
                                    <td>${value.customer_note ?? ''}</td>
                                    <td>
                                        ${action}
                                    </td>
                                </tr>
                            `);
                            salesOrderTable.row.add(tr[0]).draw();
                            number++;
                        });


                        
                        $('#revenue_idr').text(data.data._idr);
                        $('#revenue_hkd').text(data.data._hkd);
                    },
                    error: function (data) {
                        $.alert(data);
                    }
                });
            }
        }
    </script>    
@endpush


