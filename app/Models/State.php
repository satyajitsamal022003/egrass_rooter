<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function senatorialState()
    {
        return $this->hasOne(SenatorialState::class);
    }
}

class Country extends Model
{
    protected $table = 'country';
}

class SenatorialState extends Model
{
    protected $table = 'senatorial_states';
}

