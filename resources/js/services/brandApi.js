import httpClient from './httpClient.js';

export function createBrand(formData) {
    return httpClient.post('/api/brands', formData);
}

export function listBrands({page = 1, search = ''} = {}) {
    return httpClient.get('/api/brands', {
        params: {
            page,
            search,
        },
    });
}

export function getBrandDetails(id) {
    return httpClient.get(`/api/brands/${id}`);
}

export function deleteBrand(id) {
    return httpClient.delete(`/api/brands/${id}`);
}

export function putBrand(id, formData) {
    return httpClient.put(`/api/brands/${id}`,formData);
}

