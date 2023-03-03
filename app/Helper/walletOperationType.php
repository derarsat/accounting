<?php

namespace App\Helper;

enum walletOperationType: string
{
    case PaymentIn = "payment_in";
    case PaymentOut = "payment_out";
    case InvoiceIn = "invoice_in";
    case InvoiceOut = "invoice_out";
    case Expense = "expense";
}
