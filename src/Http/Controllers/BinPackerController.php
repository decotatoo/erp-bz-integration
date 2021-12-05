<?php

namespace Decotatoo\Bz\Http\Controllers;

use Decotatoo\Bz\Http\Middleware\VerifyDwiSignature;
use Decotatoo\Bz\Models\Bin;
use Decotatoo\Bz\Models\PackingSimulation;
use Decotatoo\Bz\Models\BzProduct;
use Decotatoo\Bz\Services\BinPacker\Packer;
use Illuminate\Http\Request;

/**
 * TODO:PLACEHOLDER
 */
class BinPackerController extends Controller
{
    public function __construct() {
        $this->middleware(VerifyDwiSignature::class);
    }

    public function simulate(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
        ]);

        $items = [];

        foreach ($request->items as $item) {
            $bzProduct = BzProduct::where('wp_product_id', $item['id'])->first();
            if ($bzProduct) {
                $items[] = [
                    'product' => $bzProduct,
                    'quantity' => $item['quantity'],
                ];
            }
        }

        $bins = Bin::all();

        $result = Packer::pack($bins, $items);

        $packingSimulation = new PackingSimulation();
        $packingSimulation->items = $items;
        $packingSimulation->bins = $bins;
        $packingSimulation->result = $result;
        $packingSimulation->save();

        return response()->json([
            'simulation_id' => $packingSimulation->id,
            'result' => $result,
        ]);
    }
}