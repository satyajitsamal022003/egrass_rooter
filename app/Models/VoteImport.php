<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteImport extends Model
{
    use HasFactory;
    protected $table = 'vote_imports';
    public $timestamps = false;
}
