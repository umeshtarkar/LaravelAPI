<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = 'exams';

    protected $fillable = ['name','detail','cpd_article_id','total_questions','total_marks','passing_marks','status'];
}
