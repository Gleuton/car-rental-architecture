import axios from 'axios';

export const AUTH_TOKEN_KEY = 'token';

const refreshClient = axios.create();

refreshClient.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
refreshClient.defaults.headers.common['Accept'] = 'application/json';

export function getStoredToken() {
    return localStorage.getItem(AUTH_TOKEN_KEY);
}

export function setStoredToken(token) {
    if (typeof token === 'string' && token !== '') {
        localStorage.setItem(AUTH_TOKEN_KEY, token);
        return;
    }

    localStorage.removeItem(AUTH_TOKEN_KEY);
}

export function clearStoredToken() {
    localStorage.removeItem(AUTH_TOKEN_KEY);
}

export async function refreshStoredToken() {
    const currentToken = getStoredToken();

    if (!currentToken) {
        return null;
    }

    try {
        const response = await refreshClient.post('/api/refresh', {}, {
            headers: {
                Authorization: `Bearer ${currentToken}`,
            },
        });

        const nextToken = response?.data?.token;

        if (typeof nextToken === 'string' && nextToken !== '') {
            setStoredToken(nextToken);
            return nextToken;
        }

        clearStoredToken();
        return null;
    } catch {
        clearStoredToken();
        return null;
    }
}

