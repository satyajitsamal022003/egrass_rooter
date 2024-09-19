<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddMember extends Model
{
    use HasFactory;

    protected $table = 'add_members';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'dob',
        'code',
        'occupation',
        'email_id',
        'latitude',
        'longitude',
    ];
}
