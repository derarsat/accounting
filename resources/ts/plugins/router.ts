import {createWebHashHistory, createRouter, RouteRecordRaw} from "vue-router";

const routes: RouteRecordRaw[] = [
    {
        path: "/login",
        component: () => {
            return import("../views/Login.vue");
        },
        name: "Login"
    },
    {
        path: "/",
        component: () => {
            return import("../layouts/MainLayout.vue");
        },
        children: [
            {
                path: "",
                name: "Dashboard",
                component: () => {
                    return import("../views/Analysis.vue");
                },
            },
            {
                path: "branches",
                name: "Branches",
                component: () => {
                    return import("../views/Branches.vue");
                },
            },
            {
                path: "quantities",
                name: "Quantities",
                component: () => {
                    return import("../views/Quantities.vue");
                },
            },
            {
                path: "materials",
                name: "Materials",
                component: () => {
                    return import("../views/Materials.vue");
                },
            },
            {
                path: "expenses",
                name: "Expenses",
                component: () => {
                    return import("../views/Expenses.vue");
                },
            },
            {
                path: "wallet",
                name: "Wallet",
                component: () => {
                    return import("../views/Wallet.vue");
                },
            },
            {
                path: "payments",
                name: "Payments",
                component: () => {
                    return import("../views/Payments.vue");
                },
            },
            {
                path: "invoices",
                name: "Invoices",
                component: () => {
                    return import("../views/Invoices.vue");
                },
            },
            {
                path: "categories",
                name: "Categories",
                component: () => {
                    return import("../views/Categories.vue");
                },
            },
            {
                path: "products",
                name: "Products",
                component: () => {
                    return import("../views/Products.vue");
                },
            },
            {
                path: "traders",
                name: "Traders",
                component: () => {
                    return import("../views/Traders.vue");
                },
            },
            {
                path: "currencies",
                name: "Currencies",
                component: () => {
                    return import("../views/Currencies.vue");
                },
            },
            {
                path: "events",
                name: "Events",
                component: () => {
                    return import("../views/Events.vue");
                },
            }
        ]
    }
]
const router = createRouter(
    {
        routes: routes,
        history: createWebHashHistory()
    }
)
export default router
