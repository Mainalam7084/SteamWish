<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $primaryKey = 'appid';
    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'appid',
        'name',
        'slug',
        'image',
        'price',
        'base_price',
        'discount_percent',
        'is_free',
        'rating',
        'last_updated_at',
    ];

    protected $casts = [
        'is_free'       => 'boolean',
        'last_updated_at' => 'datetime',
    ];

    /** Precio actual formateado */
    public function getPriceFormattedAttribute(): string
    {
        if ($this->price === 0) return 'Gratis';
        return number_format($this->price / 100, 2) . '€';
    }

    /** Precio base formateado */
    public function getBasePriceFormattedAttribute(): string
    {
        if ($this->base_price === 0) return 'Gratis';
        return number_format($this->base_price / 100, 2) . '€';
    }
}
