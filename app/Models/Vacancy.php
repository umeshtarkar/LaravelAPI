<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $table = 'vacancies';

    public $fillable = ['name','detail','user_id','website','type','city','status'];
}
