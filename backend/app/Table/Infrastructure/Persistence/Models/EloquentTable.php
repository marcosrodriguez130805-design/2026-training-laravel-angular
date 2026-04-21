<?php

namespace App\Table\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;
use App\Zone\Infrastructure\Persistence\Models\EloquentZone;

class EloquentTable extends Model
{
    use SoftDeletes;
    
    protected $table = 'tables';

    protected $fillable = [
        'uuid',
        'restaurant_uuid',
        'zone_uuid',
        'name',
    ];

    public function restaurant()
    {
        return $this->belongsTo(EloquentRestaurant::class, 'restaurant_uuid', 'uuid');
    }

    public function zone()
    {
        return $this->belongsTo(EloquentZone::class, 'zone_uuid', 'uuid');
    }
}
