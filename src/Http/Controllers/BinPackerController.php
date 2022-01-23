<?php

namespace Decotatoo\Bz\Http\Controllers;

use Decotatoo\Bz\Http\Middleware\VerifyDwiSignature;
use Decotatoo\Bz\Models\Bin;
use Decotatoo\Bz\Models\PackingSimulation;
use Decotatoo\Bz\Models\BzProduct;
use Decotatoo\Bz\Services\BinPacker\Packer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BinPackerController extends Controller
{
    public function __construct() {
        $this->middleware('permission:bin-packer-visualiser', ['only' => ['visualiser']]);
        $this->middleware(VerifyDwiSignature::class, ['only' => ['simulate']]);
    }

    public function visualiser(Request $request, PackingSimulation $packingSimulation) {
        $data['page_title'] = 'Packing Simulation #'. $packingSimulation->id;
        $data['packing_simulation'] = $packingSimulation;
        
        return view('bz::packing-management.packing-simulation.visualiser', $data);
    }

    public function simulate(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
        ]);

        $items = [];

        foreach ($request->items as $item) {
            $bzProduct = BzProduct::where('wp_product_id', $item['id'])->first();
            if ($bzProduct) {
                $items[] = [
                    'product' => $bzProduct->product,
                    'quantity' => $item['quantity'],
                ];
            }
        }

        $bins = Bin::all();

        try {
            $result = Packer::pack($bins, $items);
            $packingSimulation = new PackingSimulation();
            $packingSimulation->items = collect($items);
            $packingSimulation->bins = collect($bins);
            $packingSimulation->result = collect($result);
            $packingSimulation->save();


            if (count($result['unpacked'])) {
                throw new Exception("Error: Not all items could be packed");
            }

            return response()->json([
                'simulation_id' => $packingSimulation->id,
                'result' => $result['packed'],
                'visualiser_url' => route('packing-management.packing-simulation.visualiser', ['packingSimulation' => $packingSimulation->id]),
            ]);
    
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}