<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
     protected $fillable = [
        'colocation_id',
        'user_id',
        'name',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }
    public function depence()
    {
        return $this->hasMany(Depence::class, 'category_id');
    }

    public function depences()
    {
        return $this->hasMany(Depence::class, 'category_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
