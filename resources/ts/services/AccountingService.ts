import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class AccountingService {
    http = instance

    async add(walletOperation: WalletOperation): Promise<Response> {
        return await this.http.post(`${getUrl("wallet")}`, walletOperation)
    }

    async all(page, perPage, sortBy, orderBy, start, end, type): Promise<Response> {
        let url = getUrl("wallet") + `?page=${page}&per_page=${perPage}&sort=${sortBy}&order=${orderBy}&start=${start}&end=${end}&type=${type}`
        return await this.http.get(url)
    }
}
