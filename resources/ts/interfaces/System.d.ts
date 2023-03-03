interface Branch {
    name?: string;
    id?: number,
    is_default?: boolean,
}

interface SystemEventInterface {
    id?: number;
    model?: string;
    info?: string;
    user_id?: number;
    created_at?: Date;
    updated_at?: Date;
}

interface Expense {
    name?: string;
    total?: number;
    id?: number,
    branch?: Branch,
    branch_id?: number,
}
