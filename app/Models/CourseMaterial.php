<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['topic_id', 'title', 'description'];

    /**
     * Relation - Topic
     *
     * @return void
     */
    public function topic()
    {
        return $this->belongsTo(CourseTopic::class);
    }
}
