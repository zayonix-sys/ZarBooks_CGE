<?php

namespace App\Models\Accounts;

use App\Models\SysConfig\FiscalYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class TransactionDetail extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'transaction_id',
        'particular',
        'controlling_account_id',
        'dr_amount',
        'cr_amount',
        'fiscal_year_id',
    ];

    public function transactions(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

//    public function controlling_accounts()
//    {
//        return $this->hasMany(ControllingAccount::class);
//    }

    public function fiscal_years(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
    }
}
