<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    protected $table = 'news_categories';

    public $fillable = ['name','picture','status'];

    public function news(){
        return $this->hasMany(\App\Models\News::class);
    }
}
