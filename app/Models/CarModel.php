<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'models';
    protected $primaryKey = 'id';

    protected $fillable = [
        'model_name','make_id'
    ];
    protected $dates = ['deleted_at'];

    public function carMake(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CarMake::class, 'make_id');
    }

    public function modelYear(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ModelYear::class);
    }
}
