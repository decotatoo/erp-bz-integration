@extends('layouts.app')

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('website-management.index') }}">Website Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Commerce Catalog</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header">
                    <div class="col-12">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="box-title align-items-start flex-column">
                                Commerce Catalog
                                <small class="subtitle">A list of Commerce Catalog</small>
                            </h4>
                            @if (true === false && auth()->user()->can('commerce-catalog-create'))
                            <div class="text-end">
                                <a href="{{ route('website-management.commerce-catalog.create') }}"
                                    class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Add New Catalog</a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 mt-5">
                        @include('components.flash-message')
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table id="ecommerceCatalogTable" class="table no-border">
                            <thead>
                                <tr class="text-uppercase bg-lightest">
                                    <th><span class="text-dark">No</span></th>
                                    <th><span class="text-dark">Catalog</span></th>
                                    <th><span class="text-dark">Show in website</span></th>
                                    <th><span class="text-dark">Action</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($catalogs as $catalog)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $catalog->name }}</td>
                                        <td>{{ $catalog->is_published ? 'Yes' : 'No' }}</td>
                                        <td>
                                            @if (auth()->user()->can('commerce-catalog-edit'))
                                                <a href="{{ route('website-management.commerce-catalog.edit', $catalog->id) }}" class="waves-effect waves-light btn btn-info-light btn-circle mx-5">
                                                    <span class="icon-Write">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </span>
                                                </a>
                                            @endif
                                            @if (true === false && auth()->user()->can('commerce-catalog-delete'))
                                                <a href="#" class="waves-effect waves-light btn btn-danger-light btn-circle" onclick="modalDelete('Commerce Catalog', 'this catalog', '/website-management/commerce-catalog/' + {{ $catalog->id }}, '/website-management/commerce-catalog/')"><span class="icon-Trash1 fs-18"><span class="path1"></span><span class="path2"></span></span></a>
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
            $('#ecommerceCatalogTable').DataTable();
        });
    </script>
@endpush
