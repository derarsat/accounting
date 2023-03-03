<?php

namespace App\Helper;

enum eventModel: string
{
    case CurrencyEvent = "currency";
    case WalletEvent = "wallet";
    case ProductEvent = "product";
    case CategoryEvent = "category";
    case MaterialEvent = "material";
    case BranchEvent = "branch";
    case ExpenseEvent = "expense";
    case QuantityEvent = "quantity";
}
