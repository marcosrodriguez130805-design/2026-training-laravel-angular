<?php

namespace App\Product\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Family\Infrastructure\Persistence\Models\EloquentFamily;
use App\Tax\Infrastructure\Persistence\Models\EloquentTax;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;

class EloquentProduct extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'uuid',
        'image_src',
        'name',
        'price',
        'stock',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function family()
    {
        return $this->belongsTo(EloquentFamily::class, 'family_id');
    }

    public function tax()
    {
        return $this->belongsTo(EloquentTax::class, 'tax_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(EloquentRestaurant::class, 'restaurant_id');
    }
}