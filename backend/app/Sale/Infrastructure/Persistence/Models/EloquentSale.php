<?php

namespace App\Sale\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;
use App\Order\Infrastructure\Persistence\Models\EloquentOrder;
use App\User\Infrastructure\Persistence\Models\EloquentUser;

class EloquentSale extends Model
{
    use SoftDeletes;

    protected $table = 'sales';

    protected $fillable = [
        'uuid',
        'ticket_number',
        'value_date',
        'total',
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