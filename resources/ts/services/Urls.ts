const Urls = {
    login: "auth/login",
    currency: "currency",
    material: "material",
    category: "category",
    expense: "expense",
    product: "product",
    quantity: "quantity",
    branch: "branch",
    events: "events",
    trader: "trader",
    config: "config",
    wallet: "wallet-operation",
    product_variant: "add_product_variant",
    payment: "payment"
}

export function getUrl(key: keyof typeof Urls) {
    // @ts-ignore
    const baseUrl = "api/";
    return baseUrl + Urls[key];
}
