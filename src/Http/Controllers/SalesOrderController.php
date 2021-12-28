<?php

namespace Decotatoo\Bz\Http\Controllers;

use Decotatoo\Bz\Models\BzOrder;
use Decotatoo\Bz\Models\BzOrderItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class SalesOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sales-order-online-list', ['only' => ['index']]);
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
            $date_type = $request->date_type;

            $bzOrders = BzOrder::query();

            if ($start && $end && $date_type) {
                if (
                    $date_type !== 'date_created'
                    || $date_type !== 'date_paid'
                    || $date_type !== 'date_completed'
                    || $date_type !== 'date_released'
                    || $date_type !== 'date_shipment_shipped'
                    || $date_type !== 'date_shipment_delivered'
                ) {
                    $date_type = 'date_created';
                }

                $bzOrders->whereBetween($date_type, [$start, $end]);
            }

            if ($request->order_status) {
                if ($request->order_status === 'released') {
                    $bzOrders->where('date_released', '!=', null);
                } elseif ($request->order_status === 'notreleasedyet') {
                    $bzOrders->where('date_released', '=', null);
                }
            }

            $bzOrders = $bzOrders->get()->map(function ($item) {
                $data['id'] = $item->id;
                $data['so_no'] = $item->uid;
                $data['customer_name'] = $item->bzCustomer->first_name . ' ' . $item->bzCustomer->last_name;

                $rate_key = array_search('_dwi_currency_rate', array_column($item->meta_data, 'key'));
                if (false !== $rate_key) {
                    $data['currency'] = $item->meta_data[$rate_key]['value']['currency'];
                    $data['rate'] = $item->meta_data[$rate_key]['value']['rate'];
                } else {
                    $data['currency'] = $item->currency;
                }

                $data['order_date'] = Carbon::parse($item->date_created)->format('Y-m-d');
                $data['released'] = $item->released != null;

                $data['total'] = $item->total;

                return $data;
            });

            return response()->json([
                'success' => true,
                'message' => 'show List ',
                'data' => [
                    'salesOrders' => $bzOrders,
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

    public function detailProduct(BzOrder $bzOrder)
    {
        $data['page_title'] = "Detail Product Order — {$bzOrder->uid}";

        $data['products'] = $bzOrder->bzOrderItems->map(function ($item) {
            $p['code'] = $item->sku;
            $p['name'] = $item->name;
            $p['size'] = $item->bzProduct->product->size;
            $p['qty_order'] = $item->quantity;
            $p['qty_release'] = $item->productStockOuts()->count();

            return (object) $p;
        });

        
        return view('bz::sales-order.online.show-product-detail', $data);
    }
































































    // public function detailProduct($id)
    // {
    //     $data['page_title'] = "Detail Product Order";
    //     $bzOrderItems = BzOrderItem::where('bz_order_id', $id)->withCount('productStockOut')->get();

    //     $data['products'] = $bzOrderItems->map(function ($p) {
    //         $value['code'] = $p->bzProduct->prod_id;
    //         $value['name'] = $p->bzProduct->prod_name;
    //         $value['size'] = $p->bzProduct->size;
    //         $value['qty_box'] = $p->bzProduct->qty_box;
    //         $value['qty_order'] = $p->quantity ?? 0;
    //         $value['sub_total'] = $p->bzOrder->currency . ' ' . number_format($p->subtotal, 2, ',', '.');
    //         $value['price'] = $p->bzOrder->currency . ' ' . number_format($p->price, 2, ',', '.');
    //         $value['qty_release'] = $p->productStockOut_count;

    //         return (object) $value;
    //     });

    //     return view('bz::sales-order.online.show-product-detail', $data);
    // }

    public function editRelease($id)
    {
        $data['page_title'] = "Update Release Sales Order [ONLINE]";
        $data['sales_order'] = BzOrder::find($id);
        $data['customer'] = $data['sales_order']->bzCustomer;

        return view('bz::sales-order.online.release-edit', $data);
    }
}
