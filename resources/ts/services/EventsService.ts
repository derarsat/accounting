import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class EventsService {
    http = instance

    async list(): Promise<Response> {
        return await this.http.get(getUrl("events"))
    }
}
