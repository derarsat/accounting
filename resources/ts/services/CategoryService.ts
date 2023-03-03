import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class CategoryService {
    http = instance

    async all(): Promise<Response> {
        return await this.http.get(getUrl("category"))
    }

    async save(category: Category, edit: boolean): Promise<Response> {
        return edit ? await this.http.put(`${getUrl("category")}/${category.id}`, category) : await this.http.post(getUrl("category"), category)
    }

    async update(category: Category): Promise<Response> {
        return await this.http.put(getUrl("category"), category)
    }
}
