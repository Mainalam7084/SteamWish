<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceNotification extends Model
{
    protected $fillable = [
        'user_id',
        'appid',
        'game_name',
        'game_image',
        'old_price',
        'new_price',
        'discount_percent',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Precio anterior formateado en euros */
    public function getOldPriceFormattedAttribute(): string
    {
        return number_format($this->old_price / 100, 2) . '€';
    }

    /** Precio nuevo formateado en euros */
    public function getNewPriceFormattedAttribute(): string
    {
        if ($this->new_price === 0) return 'Gratis';
        return number_format($this->new_price / 100, 2) . '€';
    }

    /** ¿Está sin leer? */
    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    /** Marcar como leída */
    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }
    }
}
