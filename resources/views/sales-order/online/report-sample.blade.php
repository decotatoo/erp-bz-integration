@extends('layouts.app')

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('sales-order.index') }}">Sales Order</a></li>
<li class="breadcrumb-item active" aria-current="page">Voucher List</li>
@endsection

@section('content')


<div class="col-12">
    <div class="bg-info-light px-20 py-10 rounded mt-10">
        <div class="d-lg-flex justify-content-between align-items-center">
            <div class="col-12">
                <div class="d-flex flex-row justify-content-between">
                    <div class="d-flex align-items-start">
                        <div class="form-group" style="margin-right: 5px">
                            <label for="start_date" class="form-label">Start Date</label>

                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ Request::get('start_date') ?? date('Y-m-01') }}" placeholder="Start Date">
                        </div>

                        <div class="form-group" style="margin-right: 5px">
                            <label for="end_date" class="form-label">End Date</label>

                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ Request::get('end_date') ?? date('Y-m-t') }}" placeholder="Start Date">
                        </div>

                        <div class="form-group" style="margin-right: 5px">
                            <label for="category" class="form-label">SO Category</label>

                            <select name="category" id="category" class="form-select">
                                <option value="Regular SO">Regular SO</option>
                                <option value="Sample">Sample</option>
                                <option value="Sponsor">Sponsor</option>
                                <option value="Expired">Expired</option>
                                <option value="Spoilage">Spoilage</option>
                            </select>

                        </div>
                        <div class="form-group d-none" style="margin-right: 5px" id="loading-gif">
                            <label for="category" class="form-label">_</label>

                           <div class="badge badge-warning d-block">
                                <span style="font-size: 14px;">Loading ...</span>
                                <img src="{{asset('loading-buffering.gif')}}" alt="" width="25">
                            </div>

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

                    <table id="reportSoTable" class="table table-bordered table-sm" style="font-size: 12px; ">
                        <thead>
                            <tr class="text-uppercase bd-deco">
                                <th style="min-width: 25px"><span class="text-dark">No</span></th>
                                <th style="min-width: 25px"><span class="text-dark">SO Number</span></th>
                                <th style="min-width: 25px"><span class="text-dark">SO Category</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Customer Name</span></th>
                                <th style="min-width: 20px"><span class="text-dark">PO Number</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Input Date</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Delivery Date</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Tax</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Product Order</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Total Product Price</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Notes</span></th>
                                <th style="min-width: 80px"><span class="text-dark">Creator</span></th>
                                <th style="min-width: 80px" class="text-center"><span class="text-dark">Print</span></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-info p-20 rounded mt-30">
        <div class="d-lg-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="me-15 bg-white h-40 w-40 l-h-50 rounded text-center">
                    <h2 class="fs-18 text-info">Rp</h2>
                </div>
                <div class="d-flex flex-column fw-500">
                    <a href="#" class="text-white hover-success fs-16" id="revenue_idr">0,00</a>
                    <span class="text-white-50">Total Revenue (IDR)</span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="me-15 bg-white h-40 w-40 l-h-50 rounded text-center">
                    <h2 class="fs-18 text-info">HK$</h2>
                </div>
                <div class="d-flex flex-column fw-500">
                    <a href="#" class="text-white hover-danger fs-16" id="revenue_hkd">0,00</a>
                    <span class="text-white-50">Total Revenue (HKD)</span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="me-15 bg-white h-40 w-40 l-h-50 rounded text-center">
                    <h2 class="fs-18 text-info">€</h2>
                </div>
                <div class="d-flex flex-column fw-500">
                    <a href="#" class="text-white hover-success fs-16" id="revenue_eur">0,00</a>
                    <span class="text-white-50">Total Revenue (EUR)</span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="me-15 bg-white h-40 w-40 l-h-50 rounded text-center">
                    <h2 class="fs-18 text-info">$</h2>
                </div>
                <div class="d-flex flex-column fw-500">
                    <a href="#" class="text-white hover-info fs-16" id="revenue_usd">0,00</a>
                    <span class="text-white-50">Total Revenue (USD)</span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="me-15 bg-white h-40 w-40 l-h-50 rounded text-center">
                    <h2 class="fs-18 text-info">S$</h2>
                </div>
                <div class="d-flex flex-column fw-500">
                    <a href="#" class="text-white hover-info fs-16" id="revenue_sgd">0,00</a>
                    <span class="text-white-50">Total Revenue (SGD)</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
	<script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>

    <script>
        const reportSoTable = $('#reportSoTable').DataTable();

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

            $('#loading-gif').removeClass('d-none');
             $('#revenue_idr').text('-');
            $('#revenue_sgd').text('-');
            $('#revenue_usd').text('-');
            $('#revenue_hkd').text('-');
            $('#revenue_eur').text('-');
            startDate = fixDate($('#start_date').val());
            endDate = fixDate($('#end_date').val());
            category = $('#category').val();

            reportSoTable.clear().draw();

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
                    url: "{{ route('sales-order.reguler.list') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                        startDate: startDate.formDate,
                        endDate: endDate.formDate,
                        category,
                    },
                    success: function (data) {
                        console.log(data);


                        number = 0;
                        data.data.salesOrders.forEach((value, index) => {
                            if (value != null) {
                                number++;
                                let detailProductOrder = `
                                    <a href="#" class="btn btn-sm btn-primary btn-rounded" onclick="detailModal('Detail Product Order', ' /sales-order/reguler/detail-product/${value.id}/report', 'x-large')"><i class="fa fa-eye"></i> Detail</a>
                                `;

                                let printBtn = `
                                    <a href="/sales-order/reguler/report/print/${value.id}" class="btn btn-sm btn-info btn-rounded" target="blank"><i class="fa fa-print"></i> Print</a>
                                `

                                let tr = $(`<tr><td>${number}</td><td>${value.so_no}</td><td>${value.so_category}</td><td>${value.customer_name}</td><td>${value.po_number}</td><td>${value.input_date}</td><td>${value.estimation_delivery_date}</td><td>${value.tax}</td><td>${detailProductOrder}</td><td>${value.currency} ${value.total_price}</td><td>${value.notes}</td><td>${value.creator}</td><td>${printBtn}</td></tr>`);
                                reportSoTable.row.add(tr[0]).draw();
                            }
                        });

                        $('#revenue_idr').text(data.data.total_idr);
                        $('#revenue_sgd').text(data.data.total_sgd);
                        $('#revenue_usd').text(data.data.total_usd);
                        $('#revenue_hkd').text(data.data.total_hkd);
                        $('#revenue_eur').text(data.data.total_eur);
                         $('#loading-gif').addClass('d-none');

                    },
                    error: function (data) {
                        $.alert(data);
                    }
                });
            }
        }
    </script>
@endpush
