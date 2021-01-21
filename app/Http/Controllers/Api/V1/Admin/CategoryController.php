<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eloquent = Category::query();
        $data = (new DataTable)->of($eloquent)->make();
        return apiResponse($data, 'get data succes', true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // rules validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'description' => 'required|string|min:3|max:255',
        ]);

        // check validator
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );
        
        // roll back function
        $store = null;
        DB::transaction(function () use ($request, &$store) {
            $store = Category::create($request->only('name', 'description'));
        });

        // return if succes
        return apiResponse($store, 'create data succes', true, null, null, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return apiResponse($category, 'create data succes', true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // rules validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'description' => 'required|string|min:3|max:255',
        ]);

        // check validator
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );

        // search
        $course = Category::findOrFail($id);
        
        // update function
        $update = null;
        DB::transaction(function () use ($request, $course, &$update) {
            
            $update = $course->update($request->only('name', 'description'));
        });

        // return if succes
        return apiResponse($request->all(), 'update data succes', true, null, null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);
        $categories = Category::findOrFail($ids);
        $destroy = $categories->each(function ($category, $key) {
            $category->delete();
        });

        //
        return apiResponse($categories->pluck('id'), 'delete data succes', true);
    }
}
