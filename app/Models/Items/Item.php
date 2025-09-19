<?php

namespace App\Models\items;

use App\Http\Controllers\Items\ItemController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'part_no',
        'description',
        'unit',
        'item_category_id',
        'purchase_price',
        'sale_price',
        'status'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ItemImage::class);
    }
}
