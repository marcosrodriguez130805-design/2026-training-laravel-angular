<?php

namespace App\Zone\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;

class EloquentZone extends Model
{
    use SoftDeletes;

    protected $table = 'zones';

    protected $fillable = [
        'uuid',
        'restaurant_uuid',
        'name',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function restaurant()
    {
        return $this->belongsTo(EloquentRestaurant::class, 'restaurant_uuid', 'uuid');
    }
}