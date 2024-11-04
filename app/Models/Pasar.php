<?php

namespace App\Models;

use App\Models\User;
use App\Models\Pedagang;
use Illuminate\Support\Facades\DB;
use App\Models\RetribusiPembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Pasar extends Model
{
    use HasFactory;

    // protected $guarded = ['location'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'kecamatan_id',
        'desa_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double',
        'kecamatan_id' => 'integer',
        'desa_id' => 'integer',
    ];

    public function scopeWithTotalRetribusi($query)
    {
        return $query->addSelect(['total_retribusi' => function ($query) {
            $query->selectRaw('COALESCE(SUM(total_biaya), 0)')
                ->from('retribusi_pembayarans')
                ->whereColumn('pasar_id', 'pasars.id');
        }]);
    }

    public static function getPasarById($id, $month = null, $year = null)
    {
        return static::withTotalRetribusi($month, $year)
            ->where('pasars.id', $id)
            ->first();
    }

    public static function getTopPasar()
    {
        return static::withTotalRetribusi()
            ->orderByDesc('total_retribusi')
            ->first();
    }

    public function retribusi_pembayarans(): HasManyThrough
    {
        return $this->hasManyThrough(
            RetribusiPembayaran::class,
            Pedagang::class,
            'pasar_id', // Foreign key on pedagangs table...
            'pedagang_id', // Foreign key on retribusi_pembayarans table...
            'id', // Local key on pasars table...
            'id' // Local key on pedagangs table...
        );
    }


    public function setLocationAttribute($value)
    {
        // Kosongkan metode ini untuk mengabaikan input pada kolom location
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function pedagangs(): HasMany
    {
        return $this->hasMany(Pedagang::class);
    }

    public function retribusiPembayarans(): HasMany
    {
        return $this->hasMany(RetribusiPembayaran::class);
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_pasar');
    }
}
