<?php

namespace Decotatoo\WoocommerceIntegration\Http\Controllers;

use Decotatoo\WoocommerceIntegration\Http\Middleware\VerifyDwiSignature;
use Decotatoo\WoocommerceIntegration\Models\WiBin;
use Decotatoo\WoocommerceIntegration\Models\WiPackingSimulation;
use Decotatoo\WoocommerceIntegration\Models\WiProduct;
use Decotatoo\WoocommerceIntegration\Services\BinPacker\Packer;
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
            $wiProduct = WiProduct::where('wp_product_id', $item['id'])->first();
            if ($wiProduct) {
                $items[] = [
                    'product' => $wiProduct,
                    'quantity' => $item['quantity'],
                ];
            }
        }

        $bins = WiBin::all();

        $result = Packer::pack($bins, $items);

        $wiPackingSimulation = new WiPackingSimulation();
        $wiPackingSimulation->items = $items;
        $wiPackingSimulation->bins = $bins;
        $wiPackingSimulation->result = $result;
        $wiPackingSimulation->save();

        return response()->json([
            'simulation_id' => $wiPackingSimulation->id,
            'result' => $result,
        ]);
    }
}