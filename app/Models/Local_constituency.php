<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local_constituency extends Model
{
    use HasFactory;

    public function wards() {
        return $this->hasMany(Ward::class, 'lga_id');
    }
}
