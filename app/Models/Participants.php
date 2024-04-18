<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participants extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $primaryKey = 'participantid';
    protected $fillable = ['firstname','lastname','email','phone','company','intakegroup'];
}
