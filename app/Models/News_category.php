<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News_category extends Model
{
    use HasFactory;

    public function news()
    {
        return $this->hasMany(News_Social::class, 'newscategory', 'id');
    }
}
