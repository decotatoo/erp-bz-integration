@extends('layouts.app')

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('sales-order.index') }}">Sales Order</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('sales-order.online.base.index') }}">Online</a></li>
<li class="breadcrumb-item active" aria-current="page">Invoice</li>
@endsection

@push('styles')
    <style>
        #executiveTable_processing{
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            margin-left: -100px;
            margin-top: -26px;
            text-align: center;
            padding: 1em 0;
        }
    </style>
@endpush
@section('content')


<div class="col-12">

    <div class="d-flex flex-row justify-content-between">
        <h4 class="box-title align-items-start flex-column">
            Sales Order [ONLINE] - <span style="text-decoration-line: underline;"> Invoice PT Deco Kreasindo to Decotatoo Co., Ltd. </span>
            <small class="subtitle">Invoice of sales order online</small>
        </h4>
    </div>

    <div class="bg-info-light px-20 py-10 rounded mt-10">
        <div class="d-lg-flex justify-content-between align-items-center">
            <div class="col-12">
                <div class="d-flex flex-row justify-content-between">
                    

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
                        @if (auth()->user()->can('sales-order-online-invoice'))
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

                    <table id="invoiceSoTable" class="table table-bordered table-sm" style="font-size: 12px; ">
                        <thead>
                            <tr class="text-uppercase bd-deco">
                                <th style="min-width: 25px"><span class="text-dark">No</span></th>
                                <th style="min-width: 25px"><span class="text-dark">SO Number</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Customer Name</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Transporation Type (Order)</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Order Date</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Delivery Date</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Items</span></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div id="executiveTable_processing" class="dataTables_processing card border-2 border-dark" style="display: none;">Processing...</div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
	<script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>

    <script>
        const invoiceSoTable = $('#invoiceSoTable').DataTable();

        function fixDate(formDate) {
            const date = new Date(formDate);
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();
            return {
                string: [day, month, year].join('/'),
                date: date,
                formDate: formDate,
            }
        }

        function showFilterdData() {
            $('#executiveTable_processing').css('display', 'block');

            startDate = fixDate($('#start_date').val());
            endDate = fixDate($('#end_date').val());
            date_type = $('#date_type').val();

            invoiceSoTable.clear().draw();

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
                        date_type,
                        index_type: 'invoice-pt-to-ltd'
                    },
                    success: function (data) {
                        console.log(data);

                        number = 0;
                        data.data.salesOrders.forEach((value, index) => {
                            if (value != null) {
                                number++;
                                let detailProductOrder = `
                                    <a href="#" data-toggle="tooltip" data-placement="top" title="Detail product order" class="btn btn-sm btn-primary btn-rounded" onclick="detailModal('Detail Product Order', '${route('sales-order.online.base.detail-product', { bzOrder: value.id, view_type: 'invoice-pt-to-ltd' } )}', 'x-large')"><i class="fa fa-eye"></i> Detail</a>
                                `;

                                    
                                let td_so = ``;
                                @if (auth()->user()->can('sales-order-online-invoice-pt-to-ltd'))
                                    td_so += `
                                        <a href="${route('sales-order.online.invoice.pt-to-ltd.print', { bzOrder: value.id } )}" class="text-primary text-bold" target="_blank">
                                            ${value.so_no}
                                        </a>
                                    `;
                                @else
                                    td_so = `
                                        ${value.so_no}
                                    `;
                                @endif

                                let tr = $(`
                                    <tr>
                                        <td>${number}</td>
                                        <td>${td_so}</td>

                                        <td>${value.customer_name}</td>
                                            
                                        <td>${value.transportation_order}</td>

                                        <td>${value.date_order}</td>
                                        <td>${value.date_shipped ?? ''}</td>


                                        <td>${detailProductOrder}</td>
                                    </tr>
                                `);
                                invoiceSoTable.row.add(tr[0]).draw();
                            }
                        });
                        $('#executiveTable_processing').css('display', 'none');

                    },
                    error: function (data) {
                        $.alert(data);
                    }
                });
            }
        }
    </script>
@endpush
