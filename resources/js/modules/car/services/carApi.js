import httpClient from '@shared/services/httpClient.js';

export function createCar(payload) {
    return httpClient.post('/api/cars', payload);
}

export function listCars({page = 1, search = ''} = {}) {
    return httpClient.get('/api/cars', {
        params: {
            page,
            license_plate: search,
        },
    });
}

export function getCarDetailsByUuid(uuid) {
    return httpClient.get(`/api/cars/${uuid}`);
}

export function deleteCarByUuid(uuid) {
    return httpClient.delete(`/api/cars/${uuid}`);
}

export function putCar(uuid, payload) {
    return httpClient.put(`/api/cars/${uuid}`, payload);
}
