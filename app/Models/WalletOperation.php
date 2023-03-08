<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property walletOperationType|mixed $type
 * @property mixed $model_id
 * @property mixed|string $currency_became
 * @property mixed $currency_was
 * @property mixed $rate
 * @property float|int|mixed $amount
 * @property mixed|string $description
 * @property mixed $branch_id
 * @property mixed $currency_id
 * @property int|mixed $user_id
 */
class WalletOperation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
