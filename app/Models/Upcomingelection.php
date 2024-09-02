<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upcomingelection extends Model
{
    use HasFactory;

    public function electionType()
    {
        return $this->belongsTo(Electionresulttype::class, 'election_type_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
