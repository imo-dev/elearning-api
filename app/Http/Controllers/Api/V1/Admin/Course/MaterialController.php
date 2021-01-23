<?php

namespace App\Http\Controllers\Api\V1\Admin\Course;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {
        $eloquent = $course->materials()->with('topic');
        $data = (new DataTable)->of($eloquent)->make();
        return apiResponse($data, 'get data succes', true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Course $course)
    {
        // rules validator
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:100',
            'description' => 'required|string|min:3',
            'topic_id' => 'nullable|numeric'
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
        DB::transaction(function () use ($request, $course, &$store) {
            $store = $course->materials()->create($request->only('title', 'description', 'topic_id'));
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
    public function show(Course $course, CourseMaterial $material)
    {
        $material->load('topic');
        return apiResponse($material, 'get data succes', true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course, CourseMaterial $material)
    {
        // rules validator
        $rules = [];
        $mergeData = [];
        if ($request->has('title'))
        {
            $rules['title'] = 'required|string|min:3|max:100';
            $mergeData['title'] = $request->title;
        }
        if ($request->has('description'))
        {
            $rules['description'] = 'required|string|min:3';
            $mergeData['description'] = $request->description;
        }
        if ($request->has('topic_id'))
        {
            $rules['topic_id'] = 'nullable|numeric';
            $mergeData['topic_id'] = $request->topic_id;
        }

        // check validator
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $validator->errors(),
            422
        );
        
        // update function
        $update = null;
        DB::transaction(function () use ($request, $material, $mergeData, &$update) {
            
            $update = $material->update($mergeData);
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
    public function destroy(Course $course, $id)
    {
        $ids = explode(',', $id);
        $materials = $course->materials()->findOrFail($ids);
        $destroy = $materials->each(function ($material, $key) {
            $material->delete();
        });

        //
        return apiResponse($ids, 'delete data succes', true);
    }
}
