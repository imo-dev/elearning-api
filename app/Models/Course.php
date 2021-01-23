<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','difficulty', 'price', 'description'];

    /**
     * Relation - Categories
     *
     * @return void
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'course_categories');
    }

    /**
     * Relation - Instructors
     *
     * @return void
     */
    public function instructors()
    {
        return $this->belongsToMany(User::class, 'course_instructors');
    }

    /**
     * Relation - Inspectors
     *
     * @return void
     */
    public function inspectors()
    {
        return $this->belongsToMany(User::class, 'course_inspectors');
    }

    /**
     * Relation - Topics
     *
     * @return void
     */
    public function topics()
    {
        return $this->hasMany(CourseTopic::class);
    }

    /**
     * Relation - Materials
     *
     * @return void
     */
    public function materials()
    {
        return $this->hasMany(CourseMaterial::class);
    }
}
