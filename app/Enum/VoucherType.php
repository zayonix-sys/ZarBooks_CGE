<?php
namespace App\Enum;

enum VoucherType: string
{
    case DebitVoucher = 'DR';
    case CreditVoucher = 'CR';
    case JournalVoucher = 'JV';
}
