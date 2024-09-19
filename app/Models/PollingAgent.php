<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingAgent extends Model
{
    use HasFactory;
    protected $table = 'polling_agent';
    public $timestamps = false;

    public function pollingUnit()
    {
        return $this->belongsTo(Polling_unit::class, 'polling_units', 'id');
    }
}
