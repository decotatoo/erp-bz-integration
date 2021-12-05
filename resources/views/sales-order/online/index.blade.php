@extends('layouts.app')

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('sales-order.index') }}">Sales Order</a></li>
<li class="breadcrumb-item active" aria-current="page">Online</li>
@endsection

@section('content')

<div class="col-12">
    <div class="bg-info-light px-20 py-10 rounded mt-10">
        <div class="d-lg-flex justify-content-between align-items-center">
            <div class="col-12">
                <div class="d-flex flex-row justify-content-between">
                    <div class="align-items-start">
                        <div class="text-dark text-bold">
                            Note
                        </div>
                        <div class="mt-5">
                            <span class="text-success text-bold">Green</span>: <span class="text-dark">Released</span>
                        </div>
                        <div class="mt-0">
                            <span class="text-light text-bold">White</span>: <span class="text-dark">Not Released Yet</span>
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
                            <label for="status" class="form-label">Status</label>
        
                            <select name="status" id="status" class="form-select">
                                <option value="all" {{ (Request::get('status') == "all") ? 'selected' : ''  }}>All</option>
                                <option value="Release" {{ (Request::get('status') == "Release") ? 'selected' : ''  }}>Release</option>
                                <option value="NotReleasedYet" {{ (Request::get('NotReleasedYet') == "all") ? 'selected' : ''  }}>Not released yet</option>
                            </select>
                        </div>
                    </div>
    
                    <div class="text-end mt-20">
                        @if (auth()->user()->can('TODO-PERMISSION-WI'))
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
    
    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
                <form action="" method="POST" id="printForm" target="_blank">
                    @csrf

                    <table id="salesOrderTable" class="table no-border table-sm" style="font-size: 12px; ">
                        <thead>
                            <tr class="text-uppercase bg-lightest">
                                <th style="min-width: 1px"><span class="text-dark">No</span></th>
                                <th style="min-width: 25px"><span class="text-dark">SO Number</span></th>
                                <th style="min-width: 25px"><span class="text-dark">SO Category</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Customer Name</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Delivery Date</span></th>
                                <th style="min-width: 20px"><span class="text-dark">Product Order</span></th>
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
            status = $('#status').val();

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
                    url: "{{ route('sales-order.online.list') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                        startDate: startDate.formDate,
                        endDate: endDate.formDate,
                        status,
                    },
                    success: function (data) {
                        console.log(data);
                        number = 1;
                        data.data.salesOrders.forEach((value, index) => {
                            let detailProductOrder = `
                                <a href="#" data-toggle="tooltip" data-placement="top" title="Detail product order" class="btn btn-sm btn-primary btn-rounded" onclick="detailModal('Detail Product Order', ' /sales-order/online/detail-product/${value.id}/online', 'x-large')"><i class="fa fa-eye"></i> Detail</a>
                            `;

                            const action = `
                                <a href="#" data-toggle="tooltip" data-placement="top" title="Delete" class="waves-effect waves-light btn btn-sm btn-danger-light btn-circle" onclick="modalDelete('Sales Order', 'this data', '/sales-order/online/online-delete/${value.id}', '')"><span class="icon-Trash1 fs-18"><span class="path1"></span><span class="path2"></span></span></a>
                                <a href="/sales-order/online/edit/${value.id}" data-toggle="tooltip" data-placement="top" title="Edit" class="waves-effect waves-light btn btn-sm btn-warning-light btn-circle mx-5"><span class="icon-Write"><span class="path1"></span><span class="path2"></span></span></a>
                            `;

                            let tr = $(`<tr class="${(value.qtyReleaseTotal > 0) ? `bg-success` : ``}"><td>${number}</td><td><a href="/sales-order/online/${(value.qtyReleaseTotal > 0) ? `detail-release` : `edit-release`}/${value.id}" class="text-primary text-bold">${value.so_no}</a></td><td>${value.so_category}</td><td>${value.customer_name}</td><td>${value.po_number}</td><td>${value.estimation_delivery_date}</td><td>${detailProductOrder}</td><td>${action}</td></tr>`);
                            salesOrderTable.row.add(tr[0]).draw();
                            number++;
                        });
                        
                    },
                    error: function (data) {
                        $.alert(data);
                    }
                });
            }
        }
    </script>    
@endpush