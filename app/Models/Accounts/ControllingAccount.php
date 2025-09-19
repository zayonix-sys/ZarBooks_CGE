<?php

namespace App\Models\Accounts;

use App\Models\Sales\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Notifications\Notifiable;

class ControllingAccount extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'code',
        'parent_account_id',
        'title',
        'status',
    ];

    public function parent_accounts(): BelongsTo
    {
        return $this->belongsTo(ParentAccount::class, 'parent_account_id');
    }

    public function accounts()
    {
        return $this->hasMany(static::class);
    }

    public function transaction_details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'controlling_account_id', 'id');
    }

//    public function transactions(): BelongsToMany
//    {
//        return $this->belongsToMany(Transaction::class, 'transaction_details',
//            'controlling_account_id', 'transaction_id')
//            ->withPivot(['trn_date']);
//    }

//    public function transactions(): BelongsToMany
//    {
//        return $this->belongsToMany(Transaction::class, 'transaction_details',
//            'controlling_account_id', 'transaction_id')
//            ->withPivot(['trn_date']);
//    }


}
