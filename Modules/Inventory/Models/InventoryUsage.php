<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryUsage extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'inventory_item_id',
        'quantity',
        'purpose',
        'notes',
        'user_id'
    ];

    /**
     * Get the inventory item that owns the usage record.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the user who recorded the usage.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
} 