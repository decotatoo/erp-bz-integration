@extends('layouts.app')

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('website-management.index') }}">Website Management</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('website-management.commerce-catalog.index') }}">Commerce Catalog</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
    <div class="col-lg-6 col-12">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title">Edit Commerce Catalog</h4>
                {{-- <h4 class="box-title"></h4> --}}
            </div>

            <form class="form" action="{{ route('website-management.commerce-catalog.update', $catalog->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="box-body">
                    <div class="form-group @error('name') error @enderror">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Finish Product" name="name" id="name" value="{{ $catalog->name }}" disabled>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group @error('is_published') error @enderror">
                        <label class="form-label">Show in Website</label>

                        <select name="is_published" id="" class="form-select">
                            <option value="1" @if ($catalog->is_published) {{ 'selected' }} @endif>Yes</option>
                            <option value="0" @if (!$catalog->is_published) {{ 'selected' }} @endif>No</option>
                        </select>

                        @error('is_published')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="box-footer">
                    <a href="{{ route('website-management.commerce-catalog.index') }}" class="btn btn-dark me-1">
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

