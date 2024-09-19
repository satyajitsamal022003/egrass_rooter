<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polling_unit extends Model
{
    use HasFactory;

     // Specify the fillable fields
     protected $fillable = ['polling_name', 'polling_capacity', 'ward_details'];

     public function pollingAgents()
    {
        return $this->hasMany(PollingAgent::class, 'polling_units');
    } 
}
