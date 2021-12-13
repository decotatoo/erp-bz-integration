@extends('layouts.app')

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('inventory.index') }}">Inventory</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('inventory.unit-box.index') }}">Unit Box</a></li>
<li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<div class="col-lg-6 col-12">
    <div class="box">
        <div class="box-header with-border">
            <h4 class="box-title">Create A New Unit Box</h4>
            {{-- <h4 class="box-title"></h4> --}}
        </div>

        <form class="form" action="{{ route('inventory.unit-box.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="box-body">
                <div class="form-group @error('name') error @enderror">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" placeholder="Box Name" name="name" value="{{ old('name') }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>







                <div class="form-group @error('width') error @enderror">
                    <label class="form-label">Width (mm)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Width (mm)" name="width" value="{{ old('width') }}">
                    @error('width')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('length') error @enderror">
                    <label class="form-label">Length (mm)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Length (mm)" name="length" value="{{ old('length') }}">
                    @error('length')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group @error('height') error @enderror">
                    <label class="form-label">Height (mm)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Height (mm)" name="height" value="{{ old('height') }}">
                    @error('height')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>





                
                <div class="form-group @error('description') error @enderror">
                    <label class="form-label">Notes</label>
                    <textarea name="description" class="form-control" id="" cols="30" rows="10">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
            </div>

            <div class="box-footer">
                <a href="{{ route('inventory.unit-box.index') }}" class="btn btn-dark me-1">
                    <i class="ti-back-right"></i> Back
                </a>

                <button type="submit" class="btn btn-success">
                         Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@endpush