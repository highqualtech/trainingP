<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseToUsers extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $primaryKey = 'courseallocateid';
    protected $fillable = ['course_id','user_id','coursekey'];
}
