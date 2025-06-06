<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the inventory items supplied by this supplier.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'supplier_id');
    }
} 