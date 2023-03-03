import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class TraderService {
    http = instance

    async all(): Promise<Response> {
        return await this.http.get(getUrl("trader"))
    }

    async save(trader: Trader, edit: boolean): Promise<Response> {
        return edit ? await this.http.put(`${getUrl("trader")}/${trader.id}`, trader) : await this.http.post(getUrl("trader"), trader)
    }

    async update(trader: Trader): Promise<Response> {
        return await this.http.put(getUrl("trader"), trader)
    }
}
