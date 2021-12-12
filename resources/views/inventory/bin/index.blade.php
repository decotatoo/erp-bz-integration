@extends('layouts.app')

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('inventory.index') }}">Inventory</a></li>
    <li class="breadcrumb-item active" aria-current="page">Bin Setup</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header">
                    <div class="col-12">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="box-title align-items-start flex-column">
                                Bin Setup
                                <small class="subtitle">A list of Bin Setup</small>
                            </h4>

                            <div class="text-end">
                                @if (auth()->user()->can('bin-create'))
                                    <a href="{{ route('inventory.bin.create') }}"
                                        class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Add New Bin</a>
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
                                    <th><span class="text-dark">ref</span></th>
                                    <th><span class="text-dark">Box Name</span></th>
                                    <th><span class="text-dark">Inner Dimension (mm)</span></th>
                                    <th><span class="text-dark">Outer Dimension (mm)</span></th>
                                    <th><span class="text-dark">Empty Weight (g)</span></th>
                                    <th><span class="text-dark">Max Weight (g)</span></th>
                                    <th><span class="text-dark">Description</span></th>
                                    <th><span class="text-dark">Action</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bins as $bin)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $bin->ref }}</td>

                                        <td>{{ $bin->name }}</td>

                                        <td>
                                            <b>length</b> : {{ $bin->inner_length }} mm <br>
                                            <b>width</b> : {{ $bin->inner_width }} mm <br>
                                            <b>height</b> : {{ $bin->inner_depth }} mm <br>
                                        </td>

                                        <td>
                                            <b>length</b> : {{ $bin->outer_length }} mm <br>
                                            <b>width</b> : {{ $bin->outer_width }} mm <br>
                                            <b>height</b> : {{ $bin->outer_depth }} mm <br>
                                        </td>
                                        <td> {{ $bin->empty_weight }} gram </td>
                                        <td> {{ $bin->max_weight }} gram </td>

                                        <td>
                                            <p style="max-width: 120px; word-wrap: break-word">
                                                {!! nl2br(e($bin->description)) !!}
                                            </p>
                                        </td>

                                        <td>
                                            @if (auth()->user()->can('bin-edit'))
                                                <a href="{{ route('inventory.bin.edit', $bin->id) }}"
                                                    class="waves-effect waves-light btn btn-info-light btn-circle mx-5"><span
                                                        class="icon-Write"><span class="path1"></span><span
                                                            class="path2"></span></span></a>
                                            @endif
                                            @if (auth()->user()->can('bin-delete'))
                                            <a href="#" class="waves-effect waves-light btn btn-danger-light btn-circle" onclick="modalDelete('Bin Setup', 'this bin', '/inventory/bin/' + {{ $bin->id }}, '/inventory/bin/')"><span class="icon-Trash1 fs-18"><span class="path1"></span><span class="path2"></span></span></a>
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