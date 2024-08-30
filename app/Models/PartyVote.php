<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyVote extends Model
{
    use HasFactory;
    protected $fillable = [
        'state_id',
        'party_id',
        'vote_value',
        'election_year',
        'created_at'
    ];


    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }
}
