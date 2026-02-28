<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depence extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'montant',
        'user_id',
        'colocation_id',
        'category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}