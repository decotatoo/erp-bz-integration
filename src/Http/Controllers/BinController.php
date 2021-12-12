<?php

namespace Decotatoo\Bz\Http\Controllers;

use Decotatoo\Bz\Models\Bin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class BinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:bin-list', ['only' => 'index']);
        $this->middleware('permission:bin-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:bin-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:bin-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Bin Setup';
        $data['bins'] = Bin::orderBy('id','asc')->get();
        return view('bz::inventory.bin.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Create new Bin';
        return view('bz::inventory.bin.create', $data);
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
            'ref' => ['required', 'string', 'unique:'. Bin::class . ',ref'],
            'inner_width' => ['required', 'numeric'],
            'inner_length' => ['required', 'numeric'],
            'inner_depth' => ['required', 'numeric'],
            'outer_width' => ['required', 'numeric'],
            'outer_length' => ['required', 'numeric'],
            'outer_depth' => ['required', 'numeric'],
            'empty_weight' => ['required', 'numeric'],
            'max_weight' => ['required', 'numeric'],
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        try {

            $bin = new Bin();

            $bin->ref = $request->ref;
            $bin->inner_width = $request->inner_width;
            $bin->inner_length = $request->inner_length;
            $bin->inner_depth = $request->inner_depth;
            $bin->outer_width = $request->outer_width;
            $bin->outer_length = $request->outer_length;
            $bin->outer_depth = $request->outer_depth;
            $bin->empty_weight = $request->empty_weight;
            $bin->max_weight = $request->max_weight;

            if ($request->has('name') && !empty($request->name)) {
                $bin->name = $request->name;
            }

            if ($request->has('description') && !empty($request->description)) {
                $bin->description = $request->description;
            }

            $bin->save();

            return redirect()->route('inventory.bin.index')->with(['success' => 'New data added successfully!']);
        } catch (\Throwable $th) {
            return redirect()->route('inventory.bin.index')->with(['failed' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Bin  $bin
     * @return \Illuminate\Http\Response
     */
    public function show(Bin $bin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Bin  $bin
     * @return \Illuminate\Http\Response
     */
    public function edit(Bin $bin)
    {
        $data['page_title'] = 'Edit Data';
        $data['bin'] = $bin;
        return view('bz::inventory.bin.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Bin  $bin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bin $bin)
    {
        
        $request->validate([
            'ref' => ['required', 'string', Rule::unique(Bin::class, 'ref')->ignore($bin->id)],
            'inner_width' => ['required', 'numeric'],
            'inner_length' => ['required', 'numeric'],
            'inner_depth' => ['required', 'numeric'],
            'outer_width' => ['required', 'numeric'],
            'outer_length' => ['required', 'numeric'],
            'outer_depth' => ['required', 'numeric'],
            'empty_weight' => ['required', 'numeric'],
            'max_weight' => ['required', 'numeric'],
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        try {
            $bin->ref = $request->ref;
            $bin->inner_width = $request->inner_width;
            $bin->inner_length = $request->inner_length;
            $bin->inner_depth = $request->inner_depth;
            $bin->outer_width = $request->outer_width;
            $bin->outer_length = $request->outer_length;
            $bin->outer_depth = $request->outer_depth;
            $bin->empty_weight = $request->empty_weight;
            $bin->max_weight = $request->max_weight;

            if ($request->has('name') && !empty($request->name)) {
                $bin->name = $request->name;
            }

            if ($request->has('description') && !empty($request->description)) {
                $bin->description = $request->description;
            }

            $bin->save();

            return redirect()->route('inventory.bin.index')->with(['success' => 'Data edited successfully!']);
        } catch (\Throwable $th) {
            return redirect()->route('inventory.bin.index')->with(['failed' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Bin  $bin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bin $bin)
    {
        try {
            $bin->delete();
            Session::flash('success', 'Box Type Setup Successfully Deleted!');

            return response()->json([
                'success' => true,
                'message' => 'Box Type Setup successfully deleted',
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
