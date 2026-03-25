<?php

namespace App\User\Infrastructure\Persistence\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EloquentUser extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    protected $fillable = [
        'uuid',
        'role',
        'image_src',
        'name',
        'email',
        'password',
        'pin',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function getKeyName(): string
    {
        return 'id';
    }

    public function restaurant()
    {
        return $this->belongsTo(EloquentRestaurant::class, 'restaurant_id');
    }
}
