@extends('layouts.app')

@section('content')
<div class="col-12">
    <div>
        <h1>Packing Simulation #{{ $packing_simulation->id }} </h1>
        @if (auth()->user()->can('debug'))
            <div>
                <div class="row" >
                    <div class="form-group">
                    <label class="form-label">Raw Data</label>
                    <textarea rows="3" class="form-control">{{ $packing_simulation->result }}</textarea>
                    </div>
                </div>
            </div>
        @endif
        <div id="simulation_canvas"></div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const PACKING_SIMULATION = JSON.parse( {!! json_encode($packing_simulation->result) !!} );
    </script>

    <script src="{{ asset('js/vendor/bz/boxpacker/babylon.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bz/boxpacker/babylon.gui.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bz/boxpacker/visualiser.js') }}"></script>
@endpush

@push('styles')
<style>
    #simulation_canvas canvas {
        min-width: 100%;
    }
</style>
@endpush