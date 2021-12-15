<?php

namespace Decotatoo\Bz\Http\Controllers;

use App\Rules\Slug;
use Decotatoo\Bz\Models\CommerceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * TODO:PLACEHOLDER
 */
class CommerceCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:commerce-category-list', ['only' => 'index']);
        $this->middleware('permission:commerce-category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:commerce-category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:commerce-category-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_title'] = 'Commerce Categories';
        $data['categories'] = CommerceCategory::get();
        return view('bz::website-management.commerce-category.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Add Data';
        return view('bz::website-management.commerce-category.create', $data);
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
            'slug' => [
                new Slug(),
                'unique:'.CommerceCategory::class.',slug'
            ],
        ]);

        try {
            $category = new CommerceCategory();
            $category->name = $request->name;
            $category->slug = empty($request->slug) ? Str::slug($request->name) : $request->slug;

            $category->save();

            return redirect()->route('website-management.commerce-category.index')->with(['success' => 'New data added successfully!']);
        } catch (\Throwable $th) {
            return redirect()->route('website-management.commerce-category.index')->with(['failed' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  CommerceCategory $commerceCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CommerceCategory $commerceCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  CommerceCategory $commerceCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(CommerceCategory $commerceCategory)
    {
        $data['page_title'] = 'Edit Data';
        $data['category'] = $commerceCategory;
        return view('bz::website-management.commerce-category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  CommerceCategory $commerceCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommerceCategory $commerceCategory)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'slug' => [
                new Slug(),
                'unique:'.CommerceCategory::class.',slug,' . $commerceCategory->id,
            ],
        ]);

        try {
            $commerceCategory->name = $request->name;
            $commerceCategory->slug = empty($request->slug) ? Str::slug($request->name) : $request->slug;

            $commerceCategory->save();

            return redirect()->route('website-management.commerce-category.index')->with(['success' => 'Data edited successfully!']);
        } catch (\Throwable $th) {
            return redirect()->route('website-management.commerce-category.index')->with(['failed' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CommerceCategory  $commerceCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommerceCategory $commerceCategory)
    {
        try {
            $commerceCategory->delete();
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