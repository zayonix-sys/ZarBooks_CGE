<?php

namespace App\Models\Accounts;

use App\Models\SysConfig\FiscalYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Notifications\Notifiable;

class Transaction extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'trn_date',
        'trn_type',
        'payee',
        'messer',
        'description',
        'trn_amount',
        'fiscal_year_id',
        'user_id',
        'status'
        ];

    public function transaction_details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }


    public function fiscal_years(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }

    public function controlling_accounts(): HasMany
    {
        return $this->hasMany(ControllingAccount::class);
    }

//    public function controlling_accounts(): BelongsToMany
//    {
//        return $this->belongsToMany(ControllingAccount::class, 'transaction_details',
//            'transaction_id', 'controlling_account_id')
//            ->withPivot(['trn_date']);
//    }
}
