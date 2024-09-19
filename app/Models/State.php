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

    public function localConstituencies()
    {
        return $this->hasMany(Local_constituency::class);
    }

    public function senatorialStates()
    {
        return $this->hasMany(Senatorial_state::class);
    }

    public function federalConstituencies()
    {
        return $this->hasMany(Federal_constituency::class);
    }

    public function pollingUnits()
    {
        return $this->hasMany(Polling_unit::class);
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

