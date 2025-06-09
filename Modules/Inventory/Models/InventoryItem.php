<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItem extends Model
{

    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'supplier_id',
        'quantity',
        'unit',
        'reorder_level'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'reorder_level' => 'decimal:2'
    ];

    /**
     * Get the category that owns the inventory item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    /**
     * Get the supplier that owns the inventory item.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Get the usage records for the inventory item.
     */
    public function usageHistory(): HasMany
    {
        return $this->hasMany(InventoryUsage::class, 'inventory_item_id');
    }

    /**
     * Check if the item is low in stock.
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->minimum_quantity;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }
} 