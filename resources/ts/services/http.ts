import axios, {
    AxiosError,
    AxiosInstance,
    AxiosRequestConfig,
    AxiosResponse,
} from 'axios';

const onRequest = (config: AxiosRequestConfig): AxiosRequestConfig => {
    return config;
};

const onRequestError = (error: AxiosError): Promise<AxiosError> => {
    return Promise.resolve(error);
};

const onResponse = (response: AxiosResponse): AxiosResponse => {
    return response.data;
};

const onResponseError = (error: AxiosError): Promise<unknown> => {
    return Promise.resolve(error.response?.data);
};

function setupInterceptorsTo(axiosInstance: AxiosInstance): AxiosInstance {
    axiosInstance.interceptors.request.use(onRequest, onRequestError);
    axiosInstance.interceptors.response.use(onResponse, onResponseError);
    return axiosInstance;
}

const instance = setupInterceptorsTo(
    axios.create({
        headers: {
            // authorization: `Bearer ${getCookie('t')}`,
            // 'Accept-Encoding': 'application/json',
        },
    }),
);

export default instance;


export interface Response {
    data: unknown,
    errors: Array<unknown>,
    success: boolean
}

