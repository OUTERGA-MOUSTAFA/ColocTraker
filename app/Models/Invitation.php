<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'colocation_id',
        'created_by',
        'accepted_by',
        'token',
        'status'
    ];
}
