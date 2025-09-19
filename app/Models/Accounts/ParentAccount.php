<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class ParentAccount extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'code',
        'account_group',
        'title',
        'status',
        'is_cash_book'
    ];

    public function controlling_accounts(): HasMany
    {
        return $this->hasMany(ControllingAccount::class);
    }
}
