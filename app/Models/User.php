<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Pasar;
use App\Models\Pedagang;
use App\Traits\HasLastLoginAt;
use App\Models\RetribusiPembayaran;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable, HasLastLoginAt;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
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
            'last_login_at' => 'datetime',
        ];
    }

    public function retribusiPembayaran () : HasMany
    {
        return $this->hasMany(RetribusiPembayaran::class);
    }

    public function pasars() : BelongsToMany
    {
        return $this->belongsToMany(Pasar::class, 'user_pasar');
    }

    // Get all pedagang from assigned pasars
    public function assignedPedagang()
    {
        return Pedagang::whereIn('pasar_id', $this->pasars->pluck('id'));
    }

    // Helper method to check if user is a kolektor
    public function isKolektor(): bool
    {
        return $this->hasRole('kolektor');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }


}
