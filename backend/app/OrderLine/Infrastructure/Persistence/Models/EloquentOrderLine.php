<?php

namespace App\OrderLine\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;
use App\Order\Infrastructure\Persistence\Models\EloquentOrder;
use App\User\Infrastructure\Persistence\Models\EloquentUser;
use App\Product\Infrastructure\Persistence\Models\EloquentProduct;

class EloquentOrderLine extends Model
{
    protected $table = 'order_lines';

    protected $fillable = [
        'uuid',
        'quantity',
        'price',
        'tax_percentage',
    ];

    public function restaurant()
    {
        return $this->belongsTo(EloquentRestaurant::class, 'restaurant_id');
    }

    public function order()
    {
        return $this->belongsTo(EloquentOrder::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }
}
