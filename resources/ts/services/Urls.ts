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
    wallet: "wallet-operation"
}

export function getUrl(key: keyof typeof Urls) {
    // @ts-ignore
    const baseUrl = import.meta.env.VITE_API;
    return baseUrl + Urls[key];
}
