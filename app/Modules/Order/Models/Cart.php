<?php

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = ['customer_id'];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
