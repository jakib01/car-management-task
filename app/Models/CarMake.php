<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarMake extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'makes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
    ];
    protected $dates = ['deleted_at'];

    public function carModel(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CarModel::class);
    }
}
