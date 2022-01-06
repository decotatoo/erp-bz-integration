<?php

namespace Decotatoo\Bz\Http\Controllers;

use App\Models\ProductStockIn;
use App\Models\ProductStockOut;
use Carbon\CarbonImmutable;
use Decotatoo\Bz\Models\BzOrder;
use Decotatoo\Bz\Models\BzOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SalesOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sales-order-online-list', ['only' => ['index']]);
        // $this->middleware('permission:sales-order-online-edit', ['only' => ['editRelease']]);
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
                $data['date_released'] = $item->date_released;

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
        $data['status'] = 'online';

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

    public function editRelease(BzOrder $bzOrder)
    {
        $data['page_title'] = "Update Release Sales Order [ONLINE]";
        $data['sales_order'] = $bzOrder;
        $data['customer'] = $data['sales_order']->bzCustomer;

        return view('bz::sales-order.online.release-edit', $data);
    }

    public function listProductWithStock(BzOrder $bzOrder)
    {
        return DataTables::of($bzOrder->bzOrderItems)
            ->addColumn('product_id', function (BzOrderItem $item) {
                return $item->bzProduct->product->prod_id;
            })
            ->addColumn('product_name', function (BzOrderItem $item) {
                return $item->bzProduct->product->prod_name;
            })
            ->addColumn('size', function (BzOrderItem $item) {
                return $item->bzProduct->product->size;
            })
            ->addColumn('qty_box', function (BzOrderItem $item) {
                return $item->bzProduct->product->qty_box;
            })
            ->addColumn('qty_order', function (BzOrderItem $item) {
                return $item->quantity;
            })
            ->addColumn('qty_release', function (BzOrderItem $item) {
                return $item->productStockOuts->count();
            })
            ->addColumn('qty_in_stock', function (BzOrderItem $item) {
                // get total stock in by product id
                $stockIn = (clone $item->bzProduct->product)->productStockIn()->isReleaseable()->count();

                // get total stock out by product id
                $stockOut = (clone $item->bzProduct->product)->productStockOut()->isReleaseable()->count();

                // total stock available
                $stockAvailable = $stockIn - $stockOut;
                return $stockAvailable . ' ' . Str::of('item')->plural($stockAvailable);
            })
            ->addColumn('stock_detail', function (BzOrderItem $item) {
                $detailProduction = [];

                $stockIns = $item->bzProduct->product->productStockIn->groupBy('expired_date');
                $stockOuts = $item->bzProduct->product->productStockOut->groupBy('expired_date');

                foreach ($stockIns as $key => $value) {
                    $countStockOut = $stockOuts->has($key) ? $stockOuts[$key]->count() : 0;

                    $d = [];
                    $d['production_date'] = $value[0]->stock_in_date;
                    $d['manufacture_date'] = $value[0]->manufacture_date;
                    $d['expired_date'] = $value[0]->expired_date;
                    $d['stock_in'] = $value->count();
                    $d['stock_out'] = $countStockOut;
                    $d['stock_available'] = $value->count() - $countStockOut;
                    array_push($detailProduction, $d);
                }

                return $detailProduction;
            })
            ->addColumn('btnAction', function (BzOrderItem $item) {
                return '<div class="btn-group">
                            <a href="#"><button type="button" class="waves-effect waves-light btn btn-danger btn-sm" data-toggle="tooltip" title="Delete 1 item" onClick="deleteLastItem(\'' . $item->bzOrder->uid . '\',' . $item->bzProduct->product->id . ')"><i class="fa fa-trash"></i></button></a>
                        </div>';
            })
            ->rawColumns(['qty_in_stock', 'btnAction'])
            ->make(true);
    }

    /**
     * @see \App\Http\Controllers\SalesOrderController::releaseProduct()
     */
    public function releaseProduct(BzOrder $bzOrder, Request $request)
    {
        /**
         * $code[0] = product_id
         * $code[1] = expired_date
         */
        $code = explode('/', trim($request->code));

        if (
            substr($code[0], 0, 1) == '8'
            || substr($code[0], 0, 2) == 'KF'
            || substr($code[0], 0, 2) == 'TF'
            || substr($code[0], 0, 2) == 'TPF'
            || substr($code[0], 0, 2) == 'AW'
        ) {
            $response = (object) [
                'status' => 500,
                'message' => "System is not able to releasing this kind of product. Please contact administrator.",
            ];
            return json_encode($response);
        }

        /**
         * QCO / Color Powder Repack label
         * 
         * $code[0] = QCO
         * $code[1] = ProductStockIn::id
         */
        if ($code[0] == 'QCO') {
            $stockIn = ProductStockIn::find($code[1]);

            if (!$stockIn) {
                $response = (object) [
                    'status' => 500,
                    'message' => "Product Not Found [2]",
                ];
                return json_encode($response);
            }

            if (ProductStockOut::where('stock_in_id', $code[1])->first()) {
                $response = (object) [
                    'status' => 500,
                    'message' => "This stock has been released and can't be re-released [2]",
                ];
                return json_encode($response);
            }

            $code[0] = $stockIn->product_id;
            $code[1] = $stockIn->expired_date;
        }

        $matchedOrderedProduct = (clone $bzOrder)->bzOrderItems()->whereHas('bzProduct.product', function ($query) use ($code) {
            $query->where('prod_id', $code[0]);
        })->first();

        if (!$matchedOrderedProduct) {
            $response = (object) [
                'status' => 500,
                'message' => "Can't release unordered products [1]",
            ];
            return json_encode($response);
        }

        if (!isset($stockIn)) {
            // where have exact expired date and doesn't have stocked out
            $stockIn = (clone $matchedOrderedProduct)->bzProduct->product->productStockIn()
                ->whereDoesntHave('productStockOut')
                ->where('expired_date', $code[1])
                ->first();
        }

        if (!$stockIn) {
            $response = (object) [
                'status' => 500,
                'message' => "Product's stock not found [1]",
            ];
            return json_encode($response);
        }

        if ($matchedOrderedProduct->productStockOuts->count() >=  $matchedOrderedProduct->quantity) {
            $response = (object) [
                'status' => 500,
                'message' => "Can't release. The required release quantity has been fulfilled [1]",
            ];
            return json_encode($response);
        }

        $expiredDate = Carbon::createFromFormat('dmY', $stockIn->expired_date);
        $now = CarbonImmutable::now();
        
        // check if stock_in expire date is not less than 3 months
        try {
            $min_expired_duration = config('bz.min_month_of_stock_expire_duration');

            if ($expiredDate->lessThan($now->addMonths($min_expired_duration))) {
                $response = (object) [
                    'status' => 500,
                    'message' => "Product's stock expire date is less than {$min_expired_duration} months [1]",
                ];
                return json_encode($response);
            }
        } catch (\Throwable $th) {
            $response = (object) [
                'status' => 500,
                'message' => "Failed to parse the expire date [1]",
            ];
            return json_encode($response);
        }

        /**
         * Snippet code for product with pack_set
         */
        // if ($matchedOrderedProduct->bzProduct->product->pack_set === 'Yes') {
        //     $sheet = intval($product->qty_box);
        //     $insertStockOutProducts = [];
        //     for ($i = 1; $i <= $sheet; $i++) {
        //         array_push($insertStockOutProducts, [
        //             'product_id' => $prodId,
        //             'stock_in_date' => $stockOutDate,
        //             'expired_date' => $expiredDate,
        //             'manufacture_date' => $manufacture_date,
        //             'creator' => Auth::user()->id,
        //             'so_id' => $so_no,
        //         ]);
        //     }

        //     $saved = ProductStockOut::insert($insertStockOutProducts);

        // CalculateStock::dispatchIf($saved, $matchedOrderedProduct->bzProduct->product, 'out', $sheet)->afterCommit()->onQueue('high');
            
        //     $response = (object) [
        //         'status' => 200,
        //         'message' => "Stock out success",
        //     ];
        //     return json_encode($response);
        // }

        $stockOut = new ProductStockOut();
        $stockOut->product_id = $stockIn->product_id;
        $stockOut->stock_in_date = $stockIn->stock_in_date;
        $stockOut->expired_date = $stockIn->expired_date;
        $stockOut->manufacture_date = $stockIn->manufacture_date;
        $stockOut->creator = Auth::user()->id;
        $stockOut->so_id = $bzOrder->uid;
        $stockOut->stock_in_id = $stockIn->id;

        $matchedOrderedProduct->productStockOuts()->save($stockOut);

        $response = (object) [
            'status' => 200,
            'message' => "Stock out success",
        ];
        return json_encode($response);
    }

    public function listScanOut(BzOrder $bzOrder)
    {
        return Datatables::of($bzOrder->bzOrderItems)
            ->addColumn('product_id', function (BzOrderItem $item) {
                return $item->bzProduct->product->prod_id;
            })
            ->addColumn('product_name', function (BzOrderItem $item) {
                return $item->bzProduct->product->prod_name;
            })
            ->addColumn('size', function (BzOrderItem $item) {
                return $item->bzProduct->product->size;
            })
            ->addColumn('qty_box', function (BzOrderItem $item) {
                return $item->bzProduct->product->qty_box;
            })
            ->addColumn('qty_order', function (BzOrderItem $item) {
                return $item->quantity;
            })
            ->addColumn('qty_release', function (BzOrderItem $item) {
                return $item->productStockOuts->count();
            })

            ->make(true);
    }















    

    public function deleteLastProduct(BzOrder $bzOrder)
    {
        try {
            $productId = $request->productId;
            $soNo = $request->soNo;
            $productStockOut = ProductStockOut::where('so_id', $soNo)->where('product_id', $productId)->orderBy('id', 'DESC')->first();
            $productStockOut->delete();

            return json_encode(
                [
                    'status' => 200,
                    'message' => 'Item successfully deleted !',
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(
                [
                    'status' => 400,
                    'message' => $th->getMessage(),
                ]
            );
        }
    }








    

    // public function deleteLastProduct(Request $request)
    // {
    //     try {
    //         $productId = $request->productId;
    //         $soNo = $request->soNo;
    //         $productStockOut = ProductStockOut::where('so_id', $soNo)->where('product_id', $productId)->orderBy('id', 'DESC')->first();
    //         $productStockOut->delete();

    //         return json_encode(
    //             [
    //                 'status' => 200,
    //                 'message' => 'Item successfully deleted !',
    //             ]
    //         );
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         return json_encode(
    //             [
    //                 'status' => 400,
    //                 'message' => $th->getMessage(),
    //             ]
    //         );
    //     }
    // }
}
