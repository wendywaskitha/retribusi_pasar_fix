<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\RetribusiPembayaranItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetribusiPembayaran extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pedagang_id',
        'pasar_id',
        'user_id',
        'tanggal_bayar',
        'status',
        'total_biaya',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'total_biaya' => 'decimal:2',
    ];

    public function pedagang(): BelongsTo
    {
        return $this->belongsTo(Pedagang::class);
    }

    public function pasar(): BelongsTo
    {
        return $this->belongsTo(Pasar::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RetribusiPembayaranItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });

        static::saving(function ($model) {
            // Calculate total from items when saving
            $model->total_biaya = $model->items()->sum('biaya');
        });
    }

    // Add method to update total
    public function updateTotal()
    {
        $this->total_biaya = $this->items()->sum('biaya');
        $this->save();
    }

    public function retribusi_pembayaran_items()
    {
        return $this->hasMany(RetribusiPembayaranItem::class);
    }

    

}
