<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'status',
        'unit_of_measure_id',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function unitOfMeasure()
    {
        return $this->belongsTo(\Modules\GeneralData\Models\UnitOfMeasure::class, 'unit_of_measure_id');
    }
} 