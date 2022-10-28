<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelYear extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'model_years';
    protected $primaryKey = 'id';

    protected $fillable = [
        'model_id','year_num','expiry'
    ];
    protected $dates = ['deleted_at'];

    public function models(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CarModel::class);
    }

    public function model(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'model_id','id');
    }
}
