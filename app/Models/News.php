<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public $fillable = ['title','subtitle','text','views','picture_large','picture_small','youtube_video_url','news_category_id','user_id','status'];
}
