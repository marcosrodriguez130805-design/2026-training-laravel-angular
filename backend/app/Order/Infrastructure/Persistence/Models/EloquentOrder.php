<?php

namespace App\Order\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;
use App\Table\Infrastructure\Persistence\Models\EloquentTable;
use App\User\Infrastructure\Persistence\Models\EloquentUser;

class EloquentOrder extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'uuid',
        'status',
        'diners',
        'opened_at',
        'closed_at',
    ];

    public function restaurant()
    {
        return $this->belongsTo(EloquentRestaurant::class, 'restaurant_id');
    }

    public function table()
    {
        return $this->belongsTo(EloquentTable::class, 'table_id');
    }

    public function openedBy()
    {
        return $this->belongsTo(EloquentUser::class, 'opened_by_user_id');
    }

    public function closedBy()
    {
        return $this->belongsTo(EloquentUser::class, 'closed_by_user_id');
    }
}