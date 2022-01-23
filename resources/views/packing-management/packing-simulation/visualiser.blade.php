@extends('layouts.app')

@section('content')
<div class="col-12">
    <div>
        <h1>
            [#{{ $packing_simulation->id }}] Packing Simulation
            @if ($packing_simulation->bzOrder)
                of <a href="{{ route('sales-order.online.base.edit-release', [
                    'bzOrder' => $packing_simulation->bzOrder->id,
                ]) }}" target="_blank">
                    {{ $packing_simulation->bzOrder->uid }}
                </a>    
            @endif
        </h1>
        <div id="simulation_canvas"></div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const PACKING_SIMULATION = JSON.parse( {!! json_encode($packing_simulation->result) !!} );
    </script>
    <script src="{{ asset('vendor/bz/visualiser.js') }}"></script>
@endpush

@push('styles')
<style>
    #simulation_canvas canvas {
        min-width: 100%;
    }
</style>
<link rel="stylesheet" href="{{ asset('vendor/bz/visualiser.css') }}">
@endpush