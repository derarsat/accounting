interface Trader {
    id?: number;
    name?: string;
    phone?: string;
    address?: string;
    he_sold_us?: number;
    we_sold_him?: number;
    we_earned_from_him?: number;
    we_owe_him?: number;
    he_owes_us?: number;
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


declare enum WalletOperationType {
    PaymentIn = "payment_in",
    PaymentOut = "payment_out",
    InvoiceIn = "invoice_in",
    InvoiceOut = "invoice_out",
    Expense = "expense",
}
