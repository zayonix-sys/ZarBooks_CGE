<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemImage extends Model
{
    use HasFactory;

    protected $fillable = [
      'item_id',
        'file_name',
    ];

    public function items(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
