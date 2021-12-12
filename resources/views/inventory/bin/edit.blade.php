@extends('layouts.app')

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('inventory.index') }}">Inventory</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('inventory.bin.index') }}">Bin</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="col-lg-6 col-12">
    <div class="box">
        <div class="box-header with-border">
            <h4 class="box-title">Edit Bin</h4>
            {{-- <h4 class="box-title"></h4> --}}
        </div>

        <form class="form" action="{{ route('inventory.bin.update', $bin->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')
            
            <div class="box-body">
                <div class="form-group @error('ref') error @enderror">
                    <label class="form-label">Ref <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Box Name" name="ref" value="{{ $bin->ref }}">
                    @error('ref')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('name') error @enderror">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" placeholder="Box Name" name="name" value="{{ $bin->name }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('inner_width') error @enderror">
                    <label class="form-label">Inner Width (mm)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Inner Width (mm)" name="inner_width" value="{{ $bin->inner_width }}">
                    @error('inner_width')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('inner_length') error @enderror">
                    <label class="form-label">Inner Length (mm)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Inner Length (mm)" name="inner_length" value="{{ $bin->inner_length }}">
                    @error('inner_length')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('inner_depth') error @enderror">
                    <label class="form-label">Inner Height (mm)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Inner Height (mm)" name="inner_depth" value="{{ $bin->inner_depth }}">
                    @error('inner_depth')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('outer_width') error @enderror">
                    <label class="form-label">Outer Width (mm)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Outer Width (mm)" name="outer_width" value="{{ $bin->outer_width }}">
                    @error('outer_width')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('outer_length') error @enderror">
                    <label class="form-label">Outer Length (mm)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Outer Length (mm)" name="outer_length" value="{{ $bin->outer_length }}">
                    @error('outer_length')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('outer_depth') error @enderror">
                    <label class="form-label">Outer Height (mm)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Outer Height (mm)" name="outer_depth" value="{{ $bin->outer_depth }}">
                    @error('outer_depth')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('empty_weight') error @enderror">
                    <label class="form-label">Empty Weight (g)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Empty Weight (g)" name="empty_weight" value="{{ $bin->empty_weight }}">
                    @error('empty_weight')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('max_weight') error @enderror">
                    <label class="form-label">Max Weight (g)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Max Weight (g)" name="max_weight" value="{{ $bin->max_weight }}">
                    @error('max_weight')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('description') error @enderror">
                    <label class="form-label">Notes</label>
                    <textarea name="description" class="form-control" id="" cols="30" rows="10">{{ $bin->description }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
            </div>

            <div class="box-footer">
                <a href="{{ route('inventory.bin.index') }}" class="btn btn-dark me-1">
                    <i class="ti-back-right"></i> Back
                </a>

                <button type="submit" class="btn btn-success">
                    <i class="ti-save-alt"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@endpush