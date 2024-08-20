<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table = 'newsletter';

    protected $primaryKey = 'subscribe_id';

    public $timestamps = false;

    protected $fillable = [
        'user_email', 'created', 'status', 'user_name'
    ];
}
