<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_banned',
        'reputation_score'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function colocation(): BelongsToMany
    {
        return $this->belongsToMany(Colocation::class)
            ->withPivot('role', 'left_at')
            ->withTimestamps();
    }
    public function depences()
    {
        return $this->HasMany(Depence::class);
    }
    public function categorie()
    {
        return $this->HasMany(Categories::class);
    }

    public function invitation()
    {
        return $this->HasMany(Invitation::class);
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOwnerOf(Colocation $colocation): bool
    {
        return $this->colocations()
            ->where('colocation_id', $colocation->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }
}
