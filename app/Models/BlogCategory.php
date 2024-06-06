<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory  extends Model
{
    protected $primaryKey = 'cat_id';
    use HasFactory;
}
