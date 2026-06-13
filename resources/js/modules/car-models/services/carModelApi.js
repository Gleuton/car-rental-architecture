import httpClient from '@shared/services/httpClient.js';

export function createModelCar(formData) {
    return httpClient.post('/api/car-models', formData);
}

export function listModelCar({page = 1, search = ''} = {}) {
    return httpClient.get('/api/car-models', {
        params: {
            page,
            search,
        },
    });
}

export function getModelCarDetailsByUuid(uuid) {
    return httpClient.get(`/api/car-models/${uuid}`);
}

export function deleteModelCarByUuid(uuid) {
    return httpClient.delete(`/api/car-models/${uuid}`);
}

export function putModelCar(id, formData) {
    return httpClient.put(`/api/car-models/${id}`,formData);
}

