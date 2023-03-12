interface Trader {
    id?: number;
    name?: string;
    phone?: string;
    address?: string;
    purchased?: number;
    sold?: number;
    earned?: number;
    to_pay?: number;
    to_collect?: number;
    current_account?: number;
    created_at?: Date;
    updated_at?: Date;
}


interface WalletOperation {
    created_at?: Date;
    updated_at?: Date;
    currency_was?: number,
    currency_became?: number,
    amount?: number,
    rate?: number,
    branch_id?: number,
    user_id?: number,
    currency_id?: number,
    type?: WalletOperationType,
    model_id?: number,
    description: string,
    branch?: Branch,
    currency?: Currency

}

interface Payment {
    id?: number,
    currency?: Currency,
    trader?: Trader,
    branch?: Branch,
    rate?: number,
    currency_id?: number,
    trader_id?: number,
    amount?: number,
    currency_was?: number,
    currency_became?: number,
    current_account_was?: number,
    current_account_became?: number,
    type?: PaymentAndInvoiceType,
    created_at?: Date,
}

declare enum WalletOperationType {
    PaymentIn = "payment_in",
    PaymentOut = "payment_out",
    InvoiceIn = "invoice_in",
    InvoiceOut = "invoice_out",
    Expense = "expense",
}

declare enum PaymentAndInvoiceType {
    in = "in",
    out = "out",

}
