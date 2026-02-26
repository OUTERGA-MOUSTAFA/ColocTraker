<?php

namespace App\Models;

use App\models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Colocation extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function depence()
    {
        return $this->HasMany(Depence::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class) // clé etrange
            ->withPivot('role', 'left_at')
            ->withTimestamps();
    }
}
