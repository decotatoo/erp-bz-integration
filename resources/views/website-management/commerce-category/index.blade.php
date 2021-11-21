@extends('layouts.app')

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('website-management.index') }}">Website Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Commerce Category</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header">
                    <div class="col-12">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="box-title align-items-start flex-column">
                                Commerce Category
                                <small class="subtitle">A list of Commerce Category (woocommerce)</small>
                            </h4>
                            @if (auth()->user()->can('commerce-category-create'))
                            <div class="text-end">
                                <a href="{{ route('website-management.commerce-category.create') }}"
                                    class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Add New Commerce Category</a>
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
                        <table id="ecommerceCategoryTable" class="table no-border">
                            <thead>
                                <tr class="text-uppercase bg-lightest">
                                    <th><span class="text-dark">No</span></th>
                                    <th><span class="text-dark">Category</span></th>
                                    <th><span class="text-dark">Slug</span></th>
                                    <th><span class="text-dark">Action</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            @if (auth()->user()->can('commerce-category-edit'))
                                                <a href="{{ route('website-management.commerce-category.edit', $category->id) }}" class="waves-effect waves-light btn btn-info-light btn-circle mx-5">
                                                    <span class="icon-Write">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </span>
                                                </a>
                                            @endif
                                            @if (auth()->user()->can('commerce-category-delete'))
                                                <a href="#" class="waves-effect waves-light btn btn-danger-light btn-circle" onclick="modalDelete('Commerce Category', 'this category', '/website-management/commerce-category/' + {{ $category->id }}, '/website-management/commerce-category/')"><span class="icon-Trash1 fs-18"><span class="path1"></span><span class="path2"></span></span></a>
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
            $('#ecommerceCategoryTable').DataTable();
        });
    </script>
@endpush
