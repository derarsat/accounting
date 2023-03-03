import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class BranchService {
    http = instance

    async all(): Promise<Response> {
        return await this.http.get(getUrl("branch"))
    }

    async save(branch: Branch, edit: boolean): Promise<Response> {
        return edit ? await this.http.put(`${getUrl("branch")}/${branch.id}`, branch) : await this.http.post(getUrl("branch"), branch)
    }

    async update(branch: Branch): Promise<Response> {
        return await this.http.put(getUrl("branch"), branch)
    }
}
