<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CPDArticle extends Model
{
    protected $table = 'cpd_articles';

    protected $fillable = ['title','subtitle','text','views','picture_large','picture_small','youtube_video_url','news_category_id','user_id','status'];
}
