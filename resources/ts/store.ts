import {defineStore} from "pinia";
import http from "./services/http";
import {getUrl} from "./services/Urls";

export const useGlobalStore = defineStore('global', {
    state: () => ({
        _branches: [] as Branch[],
        _materials: [] as Material[],
        _categories: [] as Category[],
        _quantities: [] as Quantity[],
        _expenses: [] as Branch[],
        _currencies: [] as Currency[],
        _traders: [] as Trader[],
        _loading: false
    }),
    getters: {
        branches: (state) => state._branches,
        materials: (state) => state._materials,
        categories: (state) => state._categories,
        quantities: (state) => state._quantities,
        expenses: (state) => state._expenses,
        currencies: (state) => state._currencies,
        traders: (state) => state._traders,
        loading: (state) => state._loading,
    },
    actions: {
        async getConfig() {
            this._loading = true
            await http.get(getUrl("config")).then(r => {
                this._branches = r.data.branches
                this._materials = r.data.materials
                this._categories = r.data.categories
                this._quantities = r.data.quantities
                this._expenses = r.data.expenses
                this._currencies = r.data.currencies
                this._traders = r.data.traders
                this._loading = false
            })
        },
    },
})
