<?php

namespace Modules\Product\Models;

use StockMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'reorder_level',
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

    public function stockMovements()
    {
        return $this->hasMany(\Modules\Product\Models\StockMovement::class, 'product_id');
    }
} 