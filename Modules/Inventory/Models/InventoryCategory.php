<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Get the inventory items for the category.
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'category_id');
    }
} 