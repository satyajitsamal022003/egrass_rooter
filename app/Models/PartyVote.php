<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyVote extends Model
{
    use HasFactory;
    protected $fillable = [
        'state_vote_id', 'state_id', 'party_id', 'vote_value', 'election_year', 'created_at'
    ];
}
