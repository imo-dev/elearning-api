<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\DataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
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
        $model = Course::with('categories', 'instructors', 'inspectors');
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
        if ($request->has('instructors'))
        {
            $rules['instructors'] = 'required|array';
            $rules['instructors.*'] = 'required|numeric';
        }
        if ($request->has('inspectors'))
        {
            $rules['inspectors'] = 'required|array';
            $rules['inspectors.*'] = 'required|numeric';
        }

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
        $errors = [];
        $instructors = [];
        $inspectors = [];
        if ($request->has('categories')) $categories = Category::findOrFail($request->categories);
        if ($request->has('instructors'))
        {
            $instructors = User::findOrFail($request->instructors);
            $instructors->each(function ($val, $key) use (&$errors) {
                if ($val->role != 'Instructor') $errors['instructors'] = ["User {$val->id} cannt added because the role is not instructor."];
            });
        }
        if ($request->has('inspectors'))
        {
            $inspectors = User::findOrFail($request->inspectors);
            $inspectors->each(function ($val, $key) use (&$errors) {
                if ($val->role != 'Inspector') $errors['inspectors'] = ["User {$val->id} cannt added because the role is not inspector."];
            });
        }
        if (count($errors) > 0) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $errors,
            422
        );
        
        // roll back function
        $store = null;
        DB::transaction(function () use ($request, $categories, $instructors, $inspectors, &$store) {
            $store = Course::with('categories', 'instructors', 'inspectors')
                ->create($request->only('title', 'difficulty', 'price', 'description'));
            if ($request->has('categories')) $store->categories()->sync($categories->pluck('id'));
            if ($request->has('instructors')) $store->instructors()->sync($instructors->pluck('id'));
            if ($request->has('inspectors')) $store->inspectors()->sync($inspectors->pluck('id'));
        });
        $stored = (is_null($store)) ? [] : $store->toArray();

        // return if succes
        return apiResponse(
            array_merge(
                $stored,
                [
                    'categories' => $categories,
                    'instructors' => $instructors,
                    'inspectors' => $inspectors
                ]
            ),
            'create data succes',
            true,
            null,
            null,
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::with('categories', 'instructors', 'inspectors')->findOrFail($id);
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
        if ($request->has('instructors'))
        {
            $rules['instructors'] = 'required|array';
            $rules['instructors.*'] = 'required|numeric';
        }
        if ($request->has('inspectors'))
        {
            $rules['inspectors'] = 'required|array';
            $rules['inspectors.*'] = 'required|numeric';
        }
        
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
        $instructors = [];
        $inspectors = [];
        $errors = [];
        if ($request->has('categories')) $categories = Category::findOrFail($request->categories);
        if ($request->has('instructors'))
        {
            $instructors = User::findOrFail($request->instructors);
            $instructors->each(function ($val, $key) use (&$errors) {
                if ($val->role != 'Instructor') $errors['instructors'] = ["User {$val->id} cannt added because the role is not instructor."];
            });
            $instructors = $instructors->pluck('id');
        }
        if ($request->has('inspectors'))
        {
            $inspectors = User::findOrFail($request->inspectors);
            $inspectors->each(function ($val, $key) use (&$errors) {
                if ($val->role != 'Inspector') $errors['inspectors'] = ["User {$val->id} cannt added because the role is not inspector."];
            });
            $inspectors = $inspectors->pluck('id');
        }
        if (count($errors) > 0) return apiResponse(
            $request->all(),
            "Validation Fails.",
            false,
            'validation.fails',
            $errors,
            422
        );

        // update function
        $update = null;
        DB::transaction(function () use ($request, $course, $categories, $instructors, $inspectors, &$update) {
            $update = $course->update($request->only('title', 'difficulty', 'price', 'description'));
            $course->categories()->sync($categories);
            $course->instructors()->sync($instructors);
            $course->inspectors()->sync($inspectors);
        });

        // return if succes
        return apiResponse(
            array_merge(
                $course->toArray(),
                [
                    'categories' => $categories,
                    'instructors' => $instructors,
                    'inspectors' => $inspectors
                ]
            ),
            'update data succes',
            true,
            null,
            null,
            200
        );
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
