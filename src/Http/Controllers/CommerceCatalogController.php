<?php

namespace Decotatoo\Bz\Http\Controllers;

use Decotatoo\Bz\Models\CommerceCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * TODO:PLACEHOLDER
 */
class CommerceCatalogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:commerce-catalog-list', ['only' => 'index']);
        $this->middleware('permission:commerce-catalog-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:commerce-catalog-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:commerce-catalog-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_title'] = 'Commerce Catalog';
        $data['catalogs'] = CommerceCatalog::get();
        return view('bz::website-management.commerce-catalog.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Add Data';
        return view('bz::website-management.commerce-catalog.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'is_published' => ['required', 'boolean'],
        ]);

        try {
            $catalog = new CommerceCatalog();
            $catalog->name = $request->name;
            $catalog->is_published = boolval($request->is_published);

            $catalog->save();

            return redirect()->route('website-management.commerce-catalog.index')->with(['success' => 'New data added successfully!']);
        } catch (\Throwable $th) {
            return redirect()->route('website-management.commerce-catalog.index')->with(['failed' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  CommerceCatalog $commerceCatalog
     * @return \Illuminate\Http\Response
     */
    public function show(CommerceCatalog $commerceCatalog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  CommerceCatalog $commerceCatalog
     * @return \Illuminate\Http\Response
     */
    public function edit(CommerceCatalog $commerceCatalog)
    {
        $data['page_title'] = 'Edit Data';
        $data['catalog'] = $commerceCatalog;
        return view('bz::website-management.commerce-catalog.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  CommerceCatalog $commerceCatalog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommerceCatalog $commerceCatalog)
    {
        $request->validate([
            // 'name' => ['required', 'string'],
            'is_published' => ['required', 'boolean'],
        ]);

        try {
            // $commerceCatalog->name = $request->name;
            $commerceCatalog->is_published = boolval($request->is_published);
            $commerceCatalog->save();

            return redirect()->route('website-management.commerce-catalog.index')->with(['success' => 'Data edited successfully!']);
        } catch (\Throwable $th) {
            return redirect()->route('website-management.commerce-catalog.index')->with(['failed' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CommerceCatalog  $commerceCatalog
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommerceCatalog $commerceCatalog)
    {
        try {
            $commerceCatalog->delete();
            Session::flash('success', 'Commerce Category Successfully Deleted!');

            return response()->json([
                'success' => true,
                'message' => 'Commerce Category successfully deleted',
            ], 200);
        } catch (\Throwable $th) {
            Session::flash('failed', $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 200);
        }
    }
}