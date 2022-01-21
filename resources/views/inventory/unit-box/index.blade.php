@extends('layouts.app')

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('inventory.index') }}">Inventory</a></li>
    <li class="breadcrumb-item active" aria-current="page">Unit Box</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header">
                    <div class="col-12">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="box-title align-items-start flex-column">
                                Unit Box
                                <small class="subtitle">A list of Unit Box</small>
                            </h4>

                            <div class="text-end">
                                @if (auth()->user()->can('unit-box-create'))
                                    <a href="{{ route('inventory.unit-box.create') }}"
                                        class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Add New Unit Box</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-5">
                        @include('components.flash-message')
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="executiveTable" class="table table-bordered">
                            <thead>
                                <tr class="text-uppercase bd-deco">
                                    <th><span class="text-dark">No</span></th>
                                    <th><span class="text-dark">Unit Box Name</span></th>
                                    <th><span class="text-dark">Length (mm)</span></th>
                                    <th><span class="text-dark">Width (mm)</span></th>
                                    <th><span class="text-dark">Height (mm)</span></th>
                                    <th><span class="text-dark">Description</span></th>
                                    <th><span class="text-dark">Action</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unit_boxes as $unit_box)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $unit_box->name }}</td>

                                        <td>{{ $unit_box->length }}</td>
                                        <td>{{ $unit_box->width }}</td>
                                        <td>{{ $unit_box->height }}</td>
                                       
                                        <td>
                                            <p style="max-width: 120px; word-wrap: break-word">
                                                {!! nl2br(e($unit_box->description)) !!}
                                            </p>
                                        </td>

                                        <td>
                                            @if (auth()->user()->can('unit-box-edit'))
                                                <a href="{{ route('inventory.unit-box.edit', $unit_box->id) }}"
                                                    class="waves-effect waves-light btn btn-info-light btn-circle mx-5"><span
                                                        class="icon-Write"><span class="path1"></span><span
                                                            class="path2"></span></span></a>
                                            @endif
                                            @if (auth()->user()->can('unit-box-delete'))
                                            <a href="#" class="waves-effect waves-light btn btn-danger-light btn-circle" onclick="modalDelete('Unit Box', 'this unit-box', '/inventory/unit-box/' + {{ $unit_box->id }}, '/inventory/unit-box/')"><span class="icon-Trash1 fs-18"><span class="path1"></span><span class="path2"></span></span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>

    <script>
        $(function() {
            $('#executiveTable').DataTable();
        });
    </script>
@endpush


@push('name')
<style>

</style>    
@endpush