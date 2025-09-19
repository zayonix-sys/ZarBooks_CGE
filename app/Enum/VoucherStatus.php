<?php
namespace App\Enum;

enum VoucherStatus: string
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
}
