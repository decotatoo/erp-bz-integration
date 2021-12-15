<?php

namespace Decotatoo\Bz\Http\Controllers;

use Decotatoo\Bz\Models\UnitBox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UnitBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:unit-box-list', ['only' => 'index']);
        $this->middleware('permission:unit-box-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:unit-box-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:unit-box-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Unit Box Setup';
        $data['unit_boxes'] = UnitBox::orderBy('id','asc')->get();
        return view('bz::inventory.unit-box.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Create new Unit Box';
        return view('bz::inventory.unit-box.create', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  UnitBox  $unitBox
     * @return \Illuminate\Http\Response
     */
    public function show(UnitBox $unitBox)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  UnitBox  $unitBox
     * @return \Illuminate\Http\Response
     */
    public function edit(UnitBox $unitBox)
    {
        $data['page_title'] = 'Edit Data';
        $data['unit_box'] = $unitBox;
        return view('bz::inventory.unit-box.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  UnitBox  $unitBox
     * @return \Illuminate\Http\Response
     */
    public function destroy(UnitBox $unitBox)
    {
        try {
            $unitBox->delete();
            Session::flash('success', 'Unit Box Setup Successfully Deleted!');

            return response()->json([
                'success' => true,
                'message' => 'Unit Box Setup successfully deleted',
            ], 200);
        } catch (\Throwable $th) {
            Session::flash('failed', $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 200);
        }
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
            'width' => ['required', 'numeric'],
            'length' => ['required', 'numeric'],
            'height' => ['required', 'numeric'],
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        try {

            $unitBox = new UnitBox();

            $unitBox->width = $request->width;
            $unitBox->length = $request->length;
            $unitBox->height = $request->height;

            if ($request->has('name') && !empty($request->name)) {
                $unitBox->name = $request->name;
            }

            if ($request->has('description') && !empty($request->description)) {
                $unitBox->description = $request->description;
            }

            $unitBox->save();

            return redirect()->route('inventory.unit-box.index')->with(['success' => 'New data added successfully!']);
        } catch (\Throwable $th) {
            return redirect()->route('inventory.unit-box.index')->with(['failed' => $th->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  UnitBox  $unitBox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UnitBox $unitBox)
    {
        $request->validate([
            'width' => ['required', 'numeric'],
            'length' => ['required', 'numeric'],
            'height' => ['required', 'numeric'],
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        try {
            $unitBox->width = $request->width;
            $unitBox->length = $request->length;
            $unitBox->height = $request->height;

            if ($request->has('name') && !empty($request->name)) {
                $unitBox->name = $request->name;
            }

            if ($request->has('description') && !empty($request->description)) {
                $unitBox->description = $request->description;
            }

            $unitBox->save();

            return redirect()->route('inventory.unit-box.index')->with(['success' => 'Data edited successfully!']);
        } catch (\Throwable $th) {
            return redirect()->route('inventory.unit-box.index')->with(['failed' => $th->getMessage()]);
        }
    }
}
