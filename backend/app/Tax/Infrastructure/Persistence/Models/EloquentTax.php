<?php

namespace App\Tax\Infrastructure\Persistence\Models;

use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EloquentTax extends Model
{
    use SoftDeletes;

    protected $table = 'taxes';

    protected $fillable = [
        'uuid',
        'restaurant_uuid',
        'name',
        'percentage',
    ];

    public function restaurant()
    {
        return $this->belongsTo(EloquentRestaurant::class, 'restaurant_uuid', 'uuid');
    }
}