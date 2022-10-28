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

    public function carModels(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CarModel::class, 'make_id','id');
    }

    public function carModel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'id','make_id');
    }

//    public function carModel(): \Illuminate\Database\Eloquent\Relations\HasOne
//    {
//        return $this->hasOne(CarModel::class,'make_id','id');
//    }

}
