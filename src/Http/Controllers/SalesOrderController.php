<?php

namespace Decotatoo\Bz\Http\Controllers;

use Illuminate\Http\Request;

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
}
