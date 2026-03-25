<?php

namespace App\SaleLine\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;
use App\Sale\Infrastructure\Persistence\Models\EloquentSale;
use App\OrderLine\Infrastructure\Persistence\Models\EloquentOrderLine;
use App\User\Infrastructure\Persistence\Models\EloquentUser;

class EloquentSaleLine extends Model
{
    use SoftDeletes;

    protected $table = 'sales_lines';

    protected $fillable = [
        'uuid',
        'quantity',
        'price',
        'tax_percentage',
    ];

    // Relaciones
    public function restaurant()
    {
        return $this->belongsTo(EloquentRestaurant::class, 'restaurant_id');
    }

    public function sale()
    {
        return $this->belongsTo(EloquentSale::class, 'sale_id');
    }

    public function orderLine()
    {
        return $this->belongsTo(EloquentOrderLine::class, 'order_line_id');
    }

    public function user()
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }
}