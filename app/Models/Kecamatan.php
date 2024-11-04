<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function desas(): HasMany
    {
        return $this->hasMany(Desa::class);
    }

    public function pasars(): HasMany
    {
        return $this->hasMany(Pasar::class);
    }

    public function pedagangs(): HasMany
    {
        return $this->hasMany(Pedagang::class);
    }
}
