<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News_Social extends Model
{
    use HasFactory;

    protected $table = 'news_socials';
    protected $fillable = [
        'newscategory',
        'title',
        'description',
        'image',
        'meta_description',
        'video_url',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(News_category::class, 'newscategory');
    }
}
