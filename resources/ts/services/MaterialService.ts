import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class MaterialService {
    http = instance

    async all(): Promise<Response> {
        return await this.http.get(getUrl("material"))
    }

    async save(material: Material, edit: boolean): Promise<Response> {
        return edit ? await this.http.put(`${getUrl("material")}/${material.id}`, material) : await this.http.post(getUrl("material"), material)
    }

    async update(material: Material): Promise<Response> {
        return await this.http.put(getUrl("material"), material)
    }
}
