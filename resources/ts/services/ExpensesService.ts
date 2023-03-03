import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class ExpensesService {
    http = instance

    async all(): Promise<Response> {
        return await this.http.get(getUrl("expense"))
    }

    async save(expense: Expense, edit: boolean): Promise<Response> {
        return edit ? await this.http.put(`${getUrl("expense")}/${expense.id}`, expense) : await this.http.post(getUrl("expense"), expense)
    }

    async update(expense: Expense): Promise<Response> {
        return await this.http.put(getUrl("expense"), expense)
    }
}
