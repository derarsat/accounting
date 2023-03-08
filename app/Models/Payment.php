<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float|int|mixed $currency_became
 * @property float|int|mixed $current_account_became
 * @property mixed $current_account_was
 * @property mixed $currency_was
 * @property mixed $id
 * @property mixed $type
 * @property mixed $rate
 * @property mixed $amount
 * @property mixed $branch_id
 * @property mixed $trader_id
 * @property mixed $currency_id
 */
class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
}
