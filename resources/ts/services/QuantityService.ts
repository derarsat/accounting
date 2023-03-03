import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class QuantityService {
    http = instance

    async all(): Promise<Response> {
        return await this.http.get(getUrl("quantity"))
    }

    async save(quantity: Quantity, edit: boolean): Promise<Response> {
        return edit ? await this.http.put(`${getUrl("quantity")}/${quantity.id}`, quantity) : await this.http.post(getUrl("quantity"), quantity)
    }

    async update(quantity: Quantity): Promise<Response> {
        return await this.http.put(getUrl("quantity"), quantity)
    }
}
