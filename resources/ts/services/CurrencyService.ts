import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class CurrencyService {
    http = instance

    async all(): Promise<Response> {
        return await this.http.get(getUrl("currency"))
    }

    async save(currency: Currency, edit: boolean): Promise<Response> {
        return edit ? await this.http.put(`${getUrl("currency")}/${currency.id}`, currency) : await this.http.post(getUrl("currency"), currency)
    }

    async update(currency: Currency): Promise<Response> {
        return await this.http.put(getUrl("currency"), currency)
    }
}
