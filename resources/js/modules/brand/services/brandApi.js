import httpClient from '@shared/services/httpClient.js';

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

export function getBrandDetailsByUuid(uuid) {
    return httpClient.get(`/api/brands/${uuid}`);
}

export function deleteBrand(id) {
    return httpClient.delete(`/api/brands/${id}`);
}

export function deleteBrandByUuid(uuid) {
    return httpClient.delete(`/api/brands/${uuid}`);
}

export function putBrand(id, formData) {
    return httpClient.put(`/api/brands/${id}`,formData);
}

