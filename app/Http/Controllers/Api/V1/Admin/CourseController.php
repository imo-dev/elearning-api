<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
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
        $model = Course::orderBy('id', 'ASC');
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:100',
            'difficulty' => 'required|string|in:Beginner,Intermediate,Advanced',
            'price' => 'required|numeric|between:0,8',
            'description' => 'required|string|min:3',
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
            $store = Course::create($request->only('title', 'difficulty', 'price', 'description'));
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
        
        // rules validator
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:100',
            'difficulty' => 'required|string|in:Beginner,Intermediate,Advanced',
            'price' => 'required|numeric|between:0,8',
            'description' => 'required|string|min:3',
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

        $course = Course::findOrFail($id);
        // update function
        $update = null;
        DB::transaction(function () use ($request, $course, &$update) {
            
            $update = $course->update($request->only('title', 'difficulty', 'price', 'description'));
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
        $course = Course::findOrFail($id);
        $delete = $course->delete();
        return apiResponse($course, 'delete data succes', true);
    }
}
