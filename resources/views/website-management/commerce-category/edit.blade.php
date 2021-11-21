@extends('layouts.app')

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-view-grid"></i></a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('website-management.index') }}">Website Management</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('website-management.commerce-category.index') }}">Commerce Category</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
    <div class="col-lg-6 col-12">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title">Edit Commerce Category</h4>
                {{-- <h4 class="box-title"></h4> --}}
            </div>

            <form class="form" action="{{ route('website-management.commerce-category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="box-body">
                    <div class="form-group @error('name') error @enderror">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Finish Product" name="name" id="name" value="{{ $category->name }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group @error('slug') error @enderror">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" placeholder="finish-product" name="slug" id="slug" value="{{ $category->slug }}">
                        @error('slug')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="box-footer">
                    <a href="{{ route('website-management.commerce-category.index') }}" class="btn btn-dark me-1">
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
    <script>
        function stringToSlug( str ) {
            //replace all special characters | symbols with a space
            str = str.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ')
                    .toLowerCase();
            
            // trim spaces at start and end of string
            str = str.replace(/^\s+|\s+$/gm,'');
            
            // replace space with dash/hyphen
            str = str.replace(/\s+/g, '-');
            
            return str;
        }

        $(document).ready(function () {
            $('#name').on('keyup', function () {
                var name = $(this).val();
                var slug = stringToSlug(name);
                $('#slug').val(slug);
            });
        });
    </script>
@endpush
