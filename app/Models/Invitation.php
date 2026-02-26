<?php

namespace App\Models;

use App\models\User;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'colocation_id',
        'email',
        'token',
        'status',
        'expires_at',
        'accepted_by',
        'created_by',
    ];

    public function colocation(){
        return $this->belongsTo(Colocation::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
