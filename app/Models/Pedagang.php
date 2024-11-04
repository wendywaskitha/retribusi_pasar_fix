<?php

namespace App\Models;

use App\Models\RetribusiPembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedagang extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nik',
        'alamat',
        'tipepedagang_id',
        'kecamatan_id',
        'desa_id',
        'pasar_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tipepedagang_id' => 'integer',
        'kecamatan_id' => 'integer',
        'desa_id' => 'integer',
        'pasar_id' => 'integer',
    ];

    public function tipepedagang(): BelongsTo
    {
        return $this->belongsTo(Tipepedagang::class);
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function pasar(): BelongsTo
    {
        return $this->belongsTo(Pasar::class);
    }

    public function retribusiPembayarans(): HasMany
    {
        return $this->hasMany(RetribusiPembayaran::class);
    }

    public function retribusiPembayaran()
    {
        return $this->hasMany(RetribusiPembayaran::class);
    }
}
