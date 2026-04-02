import axios from 'axios';

const httpClient = axios.create();

httpClient.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

httpClient.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');

    if (token) {
        config.headers = config.headers ?? {};
        config.headers.Accept = 'application/json';
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});

export default httpClient;

