<?php

namespace Decotatoo\Bz\Http\Controllers;

use App\Models\Company;
use App\Models\ExchangeRate;
use App\Models\ProductStockIn;
use App\Models\ProductStockOut;
use Carbon\CarbonImmutable;
use Decotatoo\Bz\Models\BzOrder;
use Decotatoo\Bz\Models\BzOrderItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use Yajra\DataTables\DataTables;

class SalesOrderController extends Controller
{
    private $shipment_providers = [
        [
            'id' => null,
            'name' => 'Provider: Other',
        ],
        [
            'id' => 'jnt',
            'name' => 'J&T',
        ],
        [
            'id' => 'lion',
            'name' => 'Lion Parcel',
        ],
    ];

    public function __construct()
    {
        $this->middleware('permission:sales-order-online-list', ['only' => ['index', 'list', 'detailProduct']]);
        $this->middleware('permission:sales-order-online-release', ['only' => [
            'editRelease',
            'listProductWithStock',
            'releaseProduct',
            'listScanOut',
            'deleteLastProduct',
            'updateShipment'
        ]]);
        $this->middleware('permission:sales-order-online-detail', ['only' => ['detailRelease']]);
        $this->middleware('permission:sales-order-online-report', ['only' => ['report', 'list', 'printReport', ]]);
        $this->middleware('permission:sales-order-online-invoice', ['only' => ['indexInvoiceConsumer', 'list', 'printInvoice', 'createInvoice']]);
        $this->middleware('permission:sales-order-online-invoice-pt-to-ltd', ['only' => ['indexInvoicePtToLtd', 'list', 'printInvoicePtToLtd']]);
    }

    public function index()
    {
        $data['page_title'] = 'Sales Order [ONLINE]';
        return view('bz::sales-order.online.index', $data);
    }

    public function detailRelease(BzOrder $bzOrder)
    {
        $data['page_title'] = "Detail Release Sales Order [ONLINE]";
        $data['sales_order'] = $bzOrder;
        $data['customer'] = $bzOrder->customer;
        $data['shipments'] = $this->shipment_providers;

        return view('bz::sales-order.online.release-detail', $data);
    }

    public function editRelease(BzOrder $bzOrder)
    {
        $data['page_title'] = "Update Release Sales Order [ONLINE]";
        $data['sales_order'] = $bzOrder;
        $data['customer'] = $bzOrder->bzCustomer;

        $data['shipments'] = $this->shipment_providers;

        return view('bz::sales-order.online.release-edit', $data);
    }

    public function report()
    {
        $data['page_title'] = 'Sales Order [ONLINE] - Report';
        return view('bz::sales-order.online.report', $data);
    }

    public function indexInvoiceConsumer()
    {
        $data['page_title'] = 'Sales Order [ONLINE] - Invoice';
        return view('bz::sales-order.online.consumer.invoice-index', $data);
    }

    public function indexInvoicePtToLtd()
    {
        $data['page_title'] = 'Sales Order [ONLINE] - PT Deco Kreasindo to Decotatoo Co., Ltd.';
        return view('bz::sales-order.online.pt-to-ltd.invoice-index', $data);
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


            if ($request->index_type) {
                $bzOrders->where('date_released', '!=', null);
                if ($request->index_type === 'invoice-consumer') {
                    $bzOrders->where('date_invoice_print', '=', null);
                } elseif ($request->index_type === 'invoice-pt-to-ltd') {
                    // $bzOrders->whereJsonContains('billing->country', '!=', 'ID');
                    $bzOrders->where('billing', 'NOT LIKE', '%"country":"ID"%');
                }
            }


            // check if the order have some stocks released
            $bzOrders->with('bzOrderItems', function ($query) {
                $query->whereHas('productStockOuts');
            });

            $filteredOrders = $bzOrders->get();

            $_orders = $filteredOrders->map(function ($item) {
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

                $data['has_some_stockout'] = count($item->bzOrderItems) > 0;

                $data['date_order'] = Carbon::parse($item->date_created)->format('Y-m-d');
                $data['date_released'] = $item->date_released ? Carbon::parse($item->date_released)->format('Y-m-d') : null;
                $data['date_shipped'] = $item->date_shipment_shipped ? Carbon::parse($item->date_shipment_shipped)->format('Y-m-d') : null;

                $data['transportation_order'] = $item->shipping_lines[0]['method_title'];

                $data['total_order_value'] = number_format($item->total - $item->total_tax - $item->shipping_total, 2, ',', '.');
                $data['total_tax'] = number_format($item->total_tax, 2, ',', '.');
                $data['shipping_total'] = number_format($item->shipping_total, 2, ',', '.');

                $data['customer_note'] = $item->customer_note;

                return $data;
            });

            $_idr = $filteredOrders->where('currency', 'IDR')->sum(function ($item) {
                return $item->total - $item->total_tax - $item->shipping_total;
            });
            $_hkd = $filteredOrders->where('currency', 'HKD')->sum(function ($item) {
                return $item->total - $item->total_tax - $item->shipping_total;
            });

            return response()->json([
                'success' => true,
                'message' => 'show List ',
                'data' => [
                    'salesOrders' => $_orders,
                    '_idr' => number_format($_idr, 2, ',', '.'),
                    '_hkd' => number_format($_hkd, 2, ',', '.'),
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

    public function detailProduct(Request $request, BzOrder $bzOrder)
    {
        $data['page_title'] = "Detail Product Order ??? {$bzOrder->uid}";
        $data['view_type'] = $request->view_type ?? 'base';

        $data['products'] = $bzOrder->bzOrderItems->map(function ($item) {
            $p['code'] = $item->sku;
            $p['name'] = $item->name;
            $p['size'] = $item->bzProduct->product->size;
            $p['qty_order'] = $item->quantity;
            $p['qty_release'] = $item->productStockOuts()->count();

            $p['qty_box'] = $item->bzProduct->product->qty_box;
            $p['price'] = sprintf('%s %s', $item->bzOrder->currency, number_format($item->price, 2, ',', '.'));
            $p['sub_total'] = sprintf('%s %s', $item->bzOrder->currency, number_format($item->subtotal, 2, ',', '.'));

            return (object) $p;
        });

        return view('bz::sales-order.online.show-product-detail', $data);
    }

    public function listProductWithStock(BzOrder $bzOrder)
    {
        $dataTables = DataTables::of($bzOrder->bzOrderItems)
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
                return $stockAvailable;
            })
            ->addColumn('stock_detail', function (BzOrderItem $item) {
                $detailProduction = [];

                $stockIns = (clone $item->bzProduct->product)->productStockIn()->isReleaseable()->get()->groupBy('expired_date');
                $stockOuts = (clone $item->bzProduct->product)->productStockOut()->isReleaseable()->get()->groupBy('expired_date');

                foreach ($stockIns as $key => $value) {
                    $countStockOut = $stockOuts->has($key) ? $stockOuts[$key]->count() : 0;

                    $d = [];
                    $d['production_date'] = Carbon::createFromFormat('Y-m-d', $value[0]->stock_in_date)->toDateString();
                    $d['manufacture_date'] = Carbon::createFromFormat('dmY', $value[0]->manufacture_date)->toDateString();
                    $d['expired_date'] = Carbon::createFromFormat('dmY', $value[0]->expired_date)->toDateString();
                    $d['stock_in'] = $value->count();
                    $d['stock_out'] = $countStockOut;
                    $d['stock_available'] = $value->count() - $countStockOut;
                    array_push($detailProduction, $d);
                }

                return $detailProduction;
            })
            ->addColumn('btnAction', function (BzOrderItem $item) {
                return '<div class="btn-group">
                            <a href="#"><button type="button" class="waves-effect waves-light btn btn-danger btn-sm" data-toggle="tooltip" title="Delete 1 item" onClick="deleteLastItem(' . $item->id . ')"><i class="fa fa-trash"></i></button></a>
                        </div>';
            })
            ->rawColumns(['qty_in_stock', 'btnAction']);

        if (auth()->user()->can('sales-order-online-detail')) {
            $dataTables->addColumn('price', function (BzOrderItem $item) {
                return number_format($item->price, 2, '.', ',');
            });
            $dataTables->addColumn('price_subtotal', function (BzOrderItem $item) {
                return number_format($item->subtotal, 2, '.', ',');
            });
        }


        return $dataTables->make(true);
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

        if ($this->isOrderFulfilled($bzOrder)) {
            $response = (object) [
                'status' => 500,
                'message' => "Can't release. The order has been released [1]",
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
            $min_expired_duration = config('erp.inventory.min_month_of_sellable_expired_date');

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

        //     CalculateStock::dispatchIf($saved, $matchedOrderedProduct->bzProduct->product, 'out', $sheet)->afterCommit()->onQueue('high');

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

        $isOrderReleased = $this->releaseOrder($bzOrder);

        $response = (object) [
            'status' => 200,
            'message' => "Stock out success",
            'is_released' => $isOrderReleased,
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

    public function deleteLastProduct(BzOrder $bzOrder, Request $request)
    {
        try {
            if ($this->isOrderFulfilled($bzOrder)) {
                throw new \Exception("Can't delete. The order has been fulfilled");
            }

            $orderedItem = $bzOrder->bzOrderItems()->find($request->id);

            if (!$orderedItem) {
                throw new Exception("Can't find the ordered item");
            }

            $releasedStockToDelete = $orderedItem->productStockOuts()->orderBy('id', 'desc')->first();

            if (!$releasedStockToDelete) {
                throw new Exception("Can't find the released stock to delete");
            }

            $releasedStockToDelete->delete();

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

    private function releaseOrder(BzOrder $bzOrder)
    {
        $bzOrder->refresh();
        if ($this->isOrderFulfilled($bzOrder)) {
            $bzOrder->date_released = Carbon::now();
            $bzOrder->status = 'completed';
            if ($bzOrder->save()) {
                return true;
            }
        }

        return false;
    }

    private function isOrderFulfilled(BzOrder $bzOrder)
    {
        $orderedItems = $bzOrder->bzOrderItems;

        $fulfilled = true;

        if (!$bzOrder->date_released) {
            foreach ($orderedItems as $value) {
                if ($value->productStockOuts->count() <  $value->quantity) {
                    $fulfilled = false;
                    break;
                }
            }
        }

        return $fulfilled;
    }

    public function updateShipment(BzOrder $bzOrder, Request $request)
    {
        $request->validate([
            // 'provider' => 'in:',
            'tracking_number' => 'required|string',
            'date_shipment_shipped' => 'nullable|date',
        ]);

        $bzOrder->date_shipment_shipped = $request->date_shipment_shipped ?? Carbon::now();

        $bzOrder->shipment_provider = $request->provider;
        $bzOrder->shipment_tracking_number = $request->tracking_number;
        $bzOrder->save();

        $response = (object) [
            'status' => 200,
            'message' => "Shipment info successfully updated",
        ];
        return json_encode($response);
    }

    public function printReport(BzOrder $bzOrder)
    {
        // Choose company based on order's billing?
        $companyIssueInvoice = $bzOrder->billing['country'] === 'ID' ? 1 : 2;
        $company = Company::find($companyIssueInvoice);

        $data['company'] = $company;
        $data['sales_order'] = $bzOrder;

        $data['products'] = $bzOrder->bzOrderItems->map(function ($item) {
            return (object) [
                'code' => $item->bzProduct->product->prod_id,
                'name' => $item->bzProduct->product->prod_name,
                'size' => $item->bzProduct->product->size,
                'qty_box' => $item->bzProduct->product->qty_box,
                'qty_order' => $item->quantity,
                'qty_release' => $item->productStockOuts->count(),
                'sub_total' => $item->bzOrder->currency . ' ' . number_format($item->subtotal, 2, ',', '.'),
                'price' => $item->bzOrder->currency . ' ' . number_format($item->price, 2, ',', '.'),
            ];
        });

        $pdf = PDF::loadView('bz::sales-order.online.report-pdf', $data);

        return $pdf->stream("report-{$bzOrder->uid}.pdf");
    }

    public function printInvoice(BzOrder $bzOrder)
    {
        if (!$bzOrder->date_invoice_print) {
            $bzOrder->date_invoice_print = Carbon::now();
            $bzOrder->save();

            $bzOrder->refresh();
        }

        // Choose company based on order's billing?
        $companyIssueInvoice = $bzOrder->billing['country'] === 'ID' ? 1 : 2;
        $company = Company::find($companyIssueInvoice);

        $data['company'] = $company;
        $data['sales_order'] = $bzOrder;

        $data['products'] = $bzOrder->bzOrderItems->map(function ($item) {
            $products = [
                'code' => $item->sku,
                'name' => $item->name,
                'qty_order' => $item->quantity,
                'size' => $item->bzProduct->product->size,
                'sub_total' => $item->bzOrder->currency . ' ' . number_format($item->subtotal, 2, ',', '.'),
                'price' => $item->bzOrder->currency . ' ' . number_format($item->price, 2, ',', '.'),
            ];

            $pos = strpos(strtolower($item->bzProduct->product->qty_box), 'sheet');
            $cd = strpos(strtolower($item->bzProduct->product->prod_name), 'cd');
            $tb = strpos(strtolower($item->bzProduct->product->prod_name), 'tablet');

            if (substr($item->bzProduct->product->prod_id, 0, 2) == 'FP' && $pos !== false) {
                $products['qty'] = $item->bzProduct->product->total_box;
            } else if (substr($item->bzProduct->product->prod_id, 0, 2) == 'TR' && ($cd !== false || $tb !== false)) {
                $products['qty'] = $item->bzProduct->product->total_box;
            } else {
                if ($item->bzProduct->product->total_box == '' || $item->bzProduct->product->total_box == ' ' || $item->bzProduct->product->total_box == '0') {
                    $products['qty'] = $item->bzProduct->product->qty_box;
                } else {
                    $products['qty'] = $item->bzProduct->product->qty_box . " (" . $item->bzProduct->product->total_box . ")";
                }
            }

            return (object) $products;
        });

        $pdf = PDF::loadView('bz::sales-order.online.consumer.invoice-pdf', $data);

        return $pdf->stream("invoice-do-{$bzOrder->uid}.pdf");
    }

    public function printInvoicePtToLtd(BzOrder $bzOrder)
    {
        if ($bzOrder->billing['country'] === 'ID') {
            abort(404, 'Page not found');
        }

        if (!$bzOrder->date_invoice_print) {
            $bzOrder->date_invoice_print = Carbon::now();
            $bzOrder->save();

            $bzOrder->refresh();
        }

        $companyPT = Company::find(1);
        $companyLTD = Company::find(2);

        $data['company_pt'] = $companyPT;
        $data['company_ltd'] = $companyLTD;
        $data['sales_order'] = $bzOrder;

        //get rate usd idr
        $exchange_rate = ExchangeRate::where('from_currency', 'USD')->where('to_currency', $bzOrder->currency)->first();
        $data['exchange_rate'] = $exchange_rate;

        $data['products'] = $bzOrder->bzOrderItems->map(function ($item) use ($exchange_rate) {
            $products = [
                'code' => $item->sku,
                'name' => $item->name,
                'qty_order' => $item->quantity,
                'size' => $item->bzProduct->product->size,
                'sub_total' => $exchange_rate->from_currency . ' ' . number_format(($item->subtotal * 0.35) / $exchange_rate->rate, 2, ',', '.'),
                'price' => $exchange_rate->from_currency . ' ' . number_format(($item->price * 0.35) / $exchange_rate->rate, 2, ',', '.'),
            ];

            $pos = strpos(strtolower($item->bzProduct->product->qty_box), 'sheet');
            $cd = strpos(strtolower($item->bzProduct->product->prod_name), 'cd');
            $tb = strpos(strtolower($item->bzProduct->product->prod_name), 'tablet');

            if (substr($item->bzProduct->product->prod_id, 0, 2) == 'FP' && $pos !== false) {
                $products['qty'] = $item->bzProduct->product->total_box;
            } else if (substr($item->bzProduct->product->prod_id, 0, 2) == 'TR' && ($cd !== false || $tb !== false)) {
                $products['qty'] = $item->bzProduct->product->total_box;
            } else {
                if ($item->bzProduct->product->total_box == '' || $item->bzProduct->product->total_box == ' ' || $item->bzProduct->product->total_box == '0') {
                    $products['qty'] = $item->bzProduct->product->qty_box;
                } else {
                    $products['qty'] = $item->bzProduct->product->qty_box . " (" . $item->bzProduct->product->total_box . ")";
                }
            }

            return (object) $products;
        });

        $pdf = PDF::loadView('bz::sales-order.online.pt-to-ltd.invoice-pdf', $data);

        return $pdf->stream("invoice-do-{$bzOrder->uid}.pdf");
    }

    public function createInvoice($id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        // delete journal
        $data['salesOrder'] = $salesOrder;


        $data['customer'] = $salesOrder->customer;

        $price_total = 0;
        $tax_total = 0;
        $net_value_sub_total = 0;

        $totalQty = SalesOrderDetail::where('so_no', $salesOrder->so_no)->get()->sum('qty');
        $additionalPrice = $salesOrder->transportation_fee == 0 ? 0 : $salesOrder->transportation_fee / $totalQty;
        $additionalPrice = round($additionalPrice, 2);


        $data['products'] = $salesOrder->salesOrderDetails->map(function ($p) use ($additionalPrice) {
            $qtyRelease = DB::select(DB::raw("SELECT public.countstockoutbyso($p->product_id, '$p->so_no')"))[0]->countstockoutbyso;

            $value['code'] = $p->product->prod_id;
            $value['name'] = $p->product->prod_name;
            $value['category_prod'] = $p->product->category_prod;
            $value['size'] = $p->product->size;

            $pos = strpos(strtolower($p->product->qty_box), 'sheet');
            $cd = strpos(strtolower($p->product->prod_name), 'cd');
            $tb = strpos(strtolower($p->product->prod_name), 'tablet');

            if (substr($p->product->prod_id, 0, 2) == 'FP') {
                $value['qty'] = $p->product->total_box;
            } else if (substr($p->product->prod_id, 0, 2) == 'TR' && ($cd !== false || $tb !== false)) {
                $value['qty'] = $p->product->total_box;
            } else {
                if ($p->product->total_box == '' || $p->product->total_box == ' ' || $p->product->total_box == '0') {
                    $value['qty'] = $p->product->qty_box;
                } else {
                    $value['qty'] = $p->product->qty_box . " (" . $p->product->total_box . ")";
                }
            }

            $value['qty_order'] = $p->qty ?? 0;
            $value['currency'] = $p->salesOrder->currency;

            // Currency symbol
            if ($p->salesOrder->customer->company_id == 1) {
                if ($p->salesOrder->transportation_type == 'CIF' || $p->salesOrder->transportation_type == 'CIF By DHL') {
                    $value['unit_price'] = number_format($p->price + $additionalPrice);
                } else {
                    $value['unit_price'] = number_format($p->price);
                }
            } else {
                if ($p->salesOrder->transportation_type == 'CIF' || $p->salesOrder->transportation_type == 'CIF By DHL') {
                    $value['unit_price'] = number_format($p->price + $additionalPrice, 2);
                } else {
                    $value['unit_price'] = number_format($p->price, 2);
                }
            }

            if ($p->salesOrder->transportation_type == 'CIF' || $p->salesOrder->transportation_type == 'CIF By DHL') {
                $net_value = ($p->price + $additionalPrice) * $p->qty;
            } else {
                $net_value = $p->price * $p->qty;
            }

            if ($p->salesOrder->customer->company_id == 1) {
                $value['sub_total'] = $net_value;
            } else {
                $value['sub_total'] = $net_value;
            }

            $value['price'] = $p->price;
            return (object) $value;
        });


        $price_total = $data['products']->sum('price');
        $net_value_sub_total = $data['products']->sum('sub_total');

        $net = 0;

        $data['currency'] = $salesOrder->currency;


        if ($salesOrder->customer->company_id == 1) {
            //  total product price
            $data['total_product_price'] = $net_value_sub_total;

            // Discount
            $data['discount_count'] = $salesOrder->discount_amount . "%";
            if ($salesOrder->discount_amount > 0) {
                $tax_total = round($net_value_sub_total * ($salesOrder->discount_amount / 100));
            } else {
                $tax_total = 0;
            }
            $data['discount'] = $tax_total;

            // total net value
            if ($salesOrder->discount_amount > 0) {
                $net = $net_value_sub_total - ($net_value_sub_total * ($salesOrder->discount_amount / 100));
                $tax_total = round($net_value_sub_total * ($salesOrder->discount_amount / 100));
            } else {
                $net = $net_value_sub_total;
                $tax_total = 0;
            }
            $data['net_value'] = $net;

            // tax
            if ($salesOrder->tax ?? 'No' == 'Yes') {
                // dikasih pembulatan
                $tax_total = round($net * 0.1);
            } else {
                $tax_total = 0;
            }
            $data['tax'] = $tax_total;

            // transportation fee
            if ($salesOrder->transportation_fee != 0 && $salesOrder->transportation_type != 'CIF' && $salesOrder->transportation_type != 'CIF By DHL') {
                $data['transportation_fee'] = number_format($salesOrder->transportation_fee);
            }

            // artwork fee
            if ($salesOrder->artwork_fee != 0) {
                $data['artwork_fee'] = number_format($salesOrder->artwork_fee);
            }

            // tool fee
            if ($salesOrder->tool_fee != 0) {
                $data['tool_fee'] = number_format($salesOrder->tool_fee);
            }

            // grand total
            if ($salesOrder->transportation_type == 'CIF' || $salesOrder->transportation_type == 'CIF By DHL') {
                $revenue = $net + $salesOrder->artwork_fee + $salesOrder->tool_fee;
                $data['grand_total'] = number_format($net + $salesOrder->artwork_fee + $salesOrder->tool_fee);
            } else {
                $revenue = $net + $tax_total + $salesOrder->transportation_fee + $salesOrder->artwork_fee + $salesOrder->tool_fee;
                $data['grand_total'] = number_format($net + $tax_total + $salesOrder->transportation_fee + $salesOrder->artwork_fee + $salesOrder->tool_fee);
            }
        } else {

            //  total product price
            if ($salesOrder->transportation_type == 'CIF' || $salesOrder->transportation_type == 'CIF By DHL') {
                $data['title_total_product_price'] = "Total Product Price (CIF)";
            } else {
                $data['title_total_product_price'] = "Total Product Price";
            }
            $data['total_product_price'] = number_format($net_value_sub_total, 2);


            // Discount
            $data['discount_count'] = $salesOrder->discount_amount . "%";
            if ($salesOrder->discount_amount > 0) {
                $tax_total =  ($salesOrder->discount_amount / 100) * $net_value_sub_total;
            } else {
                // $tax_total = '- 0.00';
                $tax_total = 0;
            }
            $data['discount'] = $tax_total;
            // total net value
            if ($salesOrder->discount_amount > 0) {
                $net = $net_value_sub_total - (($salesOrder->discount_amount / 100) * $net_value_sub_total);
            } else {
                $net = $net_value_sub_total;
            }

            $data['net_value'] = $net;

            // transportation fee
            if ($salesOrder->transportation_fee != 0 && $salesOrder->transportation_type != 'CIF' && $salesOrder->transportation_type != 'CIF By DHL') {
                $data['transportation_fee'] = number_format($salesOrder->transportation_fee, 2);
            }

            // artwork fee
            if ($salesOrder->artwork_fee != 0) {
                $data['artwork_fee'] = number_format($salesOrder->artwork_fee, 2);
            }


            // tool fee
            if ($salesOrder->tool_fee != 0) {
                $data['tool_fee'] = number_format($salesOrder->tool_fee, 2);
            }

            // grand total
            if ($salesOrder->transportation_type == 'CIF' || $salesOrder->transportation_type == 'CIF By DHL') {
                $revenue = $net + $salesOrder->artwork_fee + $salesOrder->tool_fee;
                $data['grand_total'] = number_format($net + $salesOrder->artwork_fee + $salesOrder->tool_fee, 2);
            } else {
                $revenue = $net + $tax_total + $salesOrder->transportation_fee + $salesOrder->artwork_fee + $salesOrder->tool_fee;
                $data['grand_total'] = number_format($net + ($salesOrder->transportation_fee + $salesOrder->artwork_fee + $salesOrder->tool_fee), 2);
            }
        }

        $price_total_do = 0;
        $tax_total_do = 0;
        $net_value_sub_total_do = 0;
        $data['do_products'] = $salesOrder->salesOrderDetails->map(function ($p) use ($additionalPrice) {
            $qtyRelease = DB::select(DB::raw("SELECT public.countstockoutbyso($p->product_id, '$p->so_no')"))[0]->countstockoutbyso;

            $value['code'] = $p->product->prod_id;
            $value['name'] = $p->product->prod_name;
            $value['category_prod'] = $p->product->category_prod;
            $value['size'] = $p->product->size;

            $pos = strpos(strtolower($p->product->qty_box), 'sheet');
            $cd = strpos(strtolower($p->product->prod_name), 'cd');
            $tb = strpos(strtolower($p->product->prod_name), 'tablet');

            if (substr($p->product->prod_id, 0, 2) == 'FP' && $pos !== false) {
                $value['qty'] = $p->product->total_box;
            } else if (substr($p->product->prod_id, 0, 2) == 'TR' && ($cd !== false || $tb !== false)) {
                $value['qty'] = $p->product->total_box;
            } else {
                if ($p->product->total_box == '' || $p->product->total_box == ' ' || $p->product->total_box == '0') {
                    $value['qty'] = $p->product->qty_box;
                } else {
                    $value['qty'] = $p->product->qty_box . " (" . $p->product->total_box . ")";
                }
            }

            $value['qty_order'] = $p->qty ?? 0;
            $value['currency'] = $p->salesOrder->currency;

            $value['price'] = $p->price;
            return (object) $value;
        });

        $price_total_do = $data['products']->sum('price');

        // return view('sales-order.reguler.invoice-pdf', $data);
        $pdf = PDF::loadView('sales-order.reguler.invoice-pdf', $data);

        return $pdf->stream('test' . '.pdf');
    }

}
