<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Course::with('categories');
        $data = (new DataTable)->of($model)->make();
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
        // make rule if input exist
        $rules = [
            'title' => 'required|string|min:3|max:100',
            'difficulty' => 'required|string|in:Beginner,Intermediate,Advanced',
            'price' => 'required|numeric|between:0,9999999.99',
            'description' => 'required|string|min:3',
        ];
        if ($request->has('categories')) $rules['categories'] = 'required|array';

        // rules validator
        $validator = Validator::make($request->all(), $rules);

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
        $categories = [];
        if ($request->has('categories')) $categories = Category::findOrFail($request->categories);
        
        // roll back function
        $store = null;
        DB::transaction(function () use ($request, $categories, &$store) {
            $store = Course::with('categories')->create($request->only('title', 'difficulty', 'price', 'description'));
            if ($request->has('categories')) $store->categories()->sync($categories->pluck('id'));
        });
        $stored = (is_null($store)) ? [] : $store->toArray();

        // return if succes
        return apiResponse(array_merge($stored, ['categories' => $categories]), 'create data succes', true, null, null, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::findOrFail($id);
        return apiResponse($course, 'create data succes', true);
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
        // make rule if input exist
        $rules = [];
        if ($request->has('title')) $rules['title'] = 'required|string|min:3|max:100';
        if ($request->has('difficulty')) $rules['difficulty'] = 'required|string|in:Beginner,Intermediate,Advanced';
        if ($request->has('price')) $rules['price'] = 'required|numeric|between:0,9999999.99';
        if ($request->has('description')) $rules['description'] = 'required|string|min:3';
        if ($request->has('categories')) $rules['categories'] = 'required|array';
        
        // rules validator
        $validator = Validator::make($request->all(), $rules);

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
        $course = Course::findOrFail($id);
        $categories = [];
        if ($request->has('categories')) $categories = Category::findOrFail($request->categories);

        // update function
        $update = null;
        DB::transaction(function () use ($request, $course, $categories, &$update) {
            $update = $course->update($request->only('title', 'difficulty', 'price', 'description'));
            if ($request->has('categories')) $course->categories()->sync($categories->pluck('id'));
        });

        // return if succes
        return apiResponse($request->all(), 'update data succes', true, null, null, 201);
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
        $courses = Course::findOrFail($ids);
        $destroy = $courses->each(function ($course, $key) {
            $course->delete();
        });

        //
        return apiResponse($courses->pluck('id'), 'delete data succes', true);
    }
}
