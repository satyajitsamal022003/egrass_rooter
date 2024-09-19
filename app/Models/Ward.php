<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    public function localConstituency()
    {
        return $this->belongsTo(Local_constituency::class, 'lga_id');
    }

    public function pollingUnits() {
    return $this->hasMany(Polling_unit::class, 'ward_id');
}

}
