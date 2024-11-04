<?php

namespace App\Models;

use App\Models\Retribusi;
use App\Models\RetribusiPembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetribusiPembayaranItem extends Model
{
    protected $fillable = [
        'retribusi_pembayaran_id',
        'retribusi_id',
        'biaya',
    ];

    protected $casts = [
        'biaya' => 'decimal:2',
    ];

    // Add this method to ensure biaya is always set
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->biaya)) {
                $retribusi = Retribusi::find($model->retribusi_id);
                if ($retribusi) {
                    $model->biaya = $retribusi->biaya;
                } else {
                    $model->biaya = 0;
                }
            }
        });
    }

    public function retribusiPembayaran(): BelongsTo
    {
        return $this->belongsTo(RetribusiPembayaran::class);
    }

    public function retribusi(): BelongsTo
    {
        return $this->belongsTo(Retribusi::class);
    }
}
