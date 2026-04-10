<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'appid'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
