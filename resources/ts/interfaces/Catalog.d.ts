interface Product {
    id?: number;
    name?: string;
    material_id?: string;
    location?: string;
    weight?: string;
    alert_when_remaining?: number;
    total_earnings?: number;
    total_pieces_sold?: number;
    category_id?: number;
    branch_id?: number;
    created_at?: Date;
    updated_at?: Date;
    variants?: ProductVariant[];
}

interface ProductVariant {
    id?: number;
    product_id?: number;
    quantity_id?: number;
    trader_id?: number;
    quantity_value?: number;
    quantity?: Quantity,
    trader?: Trader,
    purchased?: number;
    min_price?: number;
    price?: number;
    image?: string;
    barcode?: string;
    identifier?: string;
    extra_quantity?: number,
    weight_value?: string;
    expire?: Date;
    extra_cost?: number;
    tva?: boolean;
    created_at?: Date;
    updated_at?: Date;
}

interface Quantity {
    id?: number;
    name?: string;
    number_of_pieces?: number;
    created_at?: Date;
    updated_at?: Date;
}

interface Category {
    name?: string;
    id?: number,
    branch?: Branch,
    branch_id?: number,
}

interface Material {
    name?: string;
    id?: number,
    branch?: Branch,
    branch_id?: number,
}

interface Currency {
    name?: string;
    id?: number,
    symbol?: string;
    rate?: number;
    amount?: number;
    updated_at?: Date;
}
