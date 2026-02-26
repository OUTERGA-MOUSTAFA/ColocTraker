<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depence extends Model
{
    function user()
    {
        return $this->hasMany(Depence::class);
    }
    function colocation()
    {
        return $this->hasMany(Depence::class);
    }
}
