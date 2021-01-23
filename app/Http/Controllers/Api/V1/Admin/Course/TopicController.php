<?php

namespace App\Http\Controllers\Api\V1\Admin\Course;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {
        $eloquent = $course->topics();
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
            'name' => 'required|string|min:3|max:100'
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
            $store = $course->topics()->create($request->only('name'));
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
    public function show(Course $course, CourseTopic $topic)
    {
        return apiResponse($topic, 'get data succes', true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course, CourseTopic $topic)
    {
        // rules validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
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
        
        // update function
        $update = null;
        DB::transaction(function () use ($request, $topic, &$update) {
            
            $update = $topic->update($request->only('name'));
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
        $topics = $course->topics()->findOrFail($ids);
        $destroy = $topics->each(function ($topic, $key) {
            $topic->delete();
        });

        //
        return apiResponse($ids, 'delete data succes', true);
    }
}
