<?php

namespace App\Models\SysConfig;

use App\Models\Accounts\TransactionDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FiscalYear extends Model
{
    use HasFactory, Notifiable;

    protected $fillable =
    [
      'fy_title',
      'fy_start_date',
      'fy_end_date',
      'is_active'
    ];

    public function transaction_details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
