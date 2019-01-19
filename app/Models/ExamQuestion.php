<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $table = 'exam_questions';

    public $timestamp  = true;
    protected $fillable = ['exam_id','question','answer','marks'];
}

