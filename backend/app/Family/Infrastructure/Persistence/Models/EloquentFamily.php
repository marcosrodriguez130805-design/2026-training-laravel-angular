<?php

namespace App\Family\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;

class EloquentFamily extends Model
{
    use SoftDeletes;

    protected $table = 'families';

    protected $fillable = [
        'uuid',
        'restaurant_id',
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
        return $this->belongsTo(EloquentRestaurant::class, 'restaurant_id');
    }
}