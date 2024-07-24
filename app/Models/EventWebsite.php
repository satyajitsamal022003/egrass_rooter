<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventWebsite extends Model
{
    use HasFactory;

    protected $table = 'event_website';
    public $timestamps = false;
}
