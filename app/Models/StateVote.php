<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateVote extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'state_id', 'accredited_votes', 'valid_votes', 'election_year', 'created'
    ];
}
