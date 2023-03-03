import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class ProductService {
    http = instance

    async all(page, perPage, sortBy, orderBy): Promise<Response> {
        let url = getUrl("product") + `?page=${page}&per_page=${perPage}&sort=${sortBy}&order=${orderBy}`
        return await this.http.get(url)
    }

    async save(product: Product, edit: boolean): Promise<Response> {
        return edit ? await this.http.put(`${getUrl("product")}/${product.id}`, product) : await this.http.post(getUrl("product"), product)
    }

    async update(product: Product): Promise<Response> {
        return await this.http.put(getUrl("product"), product)
    }
}
