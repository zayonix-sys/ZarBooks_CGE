<?php

namespace App\Models\Sales;

use App\Models\Accounts\ControllingAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'contact',
        'email',
        'cnic',
        'ntn',
        'strn',
        'controlling_account_id',
        'status'
        ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(ControllingAccount::class, 'controlling_account_id', 'id');
    }
}
