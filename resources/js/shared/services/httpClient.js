import axios from 'axios';
import { clearStoredToken, getStoredToken, refreshStoredToken } from '@shared/services/authRefreshService.js';

const httpClient = axios.create();

function redirectToLogin() {
    clearStoredToken();

    if (window.location.pathname !== '/login') {
        window.location.assign('/login');
    }
}

httpClient.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
httpClient.defaults.headers.common['Accept'] = 'application/json';

httpClient.interceptors.request.use((config) => {
    const token = getStoredToken();

    if (token) {
        config.headers = config.headers ?? {};
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});

httpClient.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error?.config;
        const status = error?.response?.status;

        if (
            status !== 401
            || !originalRequest
            || originalRequest._retry
            || originalRequest.url?.includes('/api/refresh')
        ) {
            return Promise.reject(error);
        }

        originalRequest._retry = true;

        const refreshedToken = await refreshStoredToken();

        if (!refreshedToken) {
            clearStoredToken();
            redirectToLogin();
            return Promise.reject(error);
        }

        originalRequest.headers = originalRequest.headers ?? {};
        originalRequest.headers.Authorization = `Bearer ${refreshedToken}`;

        return httpClient(originalRequest);
    },
);

export default httpClient;

