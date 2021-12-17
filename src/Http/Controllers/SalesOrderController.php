<?php

namespace Decotatoo\Bz\Http\Controllers;

use Decotatoo\Bz\Models\BzOrder;
use Decotatoo\Bz\Models\BzOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class SalesOrderController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:TODO-PERMISSION-WI', ['only' => ['index']]);
    }

    public function index()
    {
        $data['page_title'] = 'Sales Order [ONLINE]';
        return view('bz::sales-order.online.index', $data);
    }

    public function list(Request $request)
    {
        try {
            $start = date('Y-m-d', strtotime($request->startDate));
            $end = date('Y-m-d', strtotime($request->endDate));

            if ($start && $end) {
                $bzOrders = BzOrder::whereBetween('date_created', [$start, $end])->orderBy('id', 'desc')->get();
            } else {
                $bzOrders = BzOrder::orderBy('id', 'desc')->get();
            }

            $bzOrders = $bzOrders->map(function ($item) {
                $value['id'] = $item->id;
                $value['so_no'] = $item->uid;
                $value['customer_name'] = $item->bzCustomer->first_name . ' ' . $item->bzCustomer->last_name;
                $value['currency'] = $item->currency;
                $value['order_date'] = Carbon::parse($item->date_created)->format('Y-m-d');
                $value['released'] = $item->released;

                return $value;
            });

            $totalIdr = $bzOrders->where('currency', 'IDR')->sum('total');
            $totalHkd = $bzOrders->where('currency', 'HKD')->sum('total');

            return response()->json([
                'success' => true,
                'message' => 'show List ',
                'data' => [
                    'salesOrders' => $bzOrders,
                    'total_idr' => number_format($totalIdr, 2, ',', '.'),
                    'total_hkd' => number_format($totalHkd, 2, ',', '.'),
                ],
            ], 200);
        } catch (\Throwable $th) {
            Session::flash('failed', $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function detailProduct($id)
    {
        $data['page_title'] = "Detail Product Order";
        $bzOrderItems = BzOrderItem::where('bz_order_id', $id)->withCount('productStockOut')->get();

        $data['products'] = $bzOrderItems->map(function ($p) {
            $value['code'] = $p->bzProduct->prod_id;
            $value['name'] = $p->bzProduct->prod_name;
            $value['size'] = $p->bzProduct->size;
            $value['qty_box'] = $p->bzProduct->qty_box;
            $value['qty_order'] = $p->quantity ?? 0;
            $value['sub_total'] = $p->bzOrder->currency . ' ' . number_format($p->subtotal, 2, ',', '.');
            $value['price'] = $p->bzOrder->currency . ' ' . number_format($p->price, 2, ',', '.');
            $value['qty_release'] = $p->productStockOut_count;

            return (object) $value;
        });

        return view('bz::sales-order.online.show-product-detail', $data);
    }

    public function editRelease($id)
    {
        $data['page_title'] = "Update Release Sales Order [ONLINE]";
        $data['sales_order'] = BzOrder::find($id);
        $data['customer'] = $data['sales_order']->bzCustomer;

        return view('bz::sales-order.online.release-edit', $data);
    }
}
