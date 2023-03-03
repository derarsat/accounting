import instance, {Response} from "./http";
import {getUrl} from "./Urls";

export class AuthService {
    http = instance

    async login(body: LoginBody): Promise<Response> {
        return await this.http.post(getUrl("login"), body)
    }
}
