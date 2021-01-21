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
}
