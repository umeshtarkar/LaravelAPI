<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExam extends Model
{
    protected $table = 'user_exams';

    protected $fillable = ['user_id','exam_id','question_id','answer','marks','city','status'];
}
