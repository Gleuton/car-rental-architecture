import httpClient from "@shared/services/httpClient.js";

export function listCars({page = 1, licensePlate = ''} = {}) {
    return httpClient.get('/api/cars', {
        params: {
            page,
            license_plate: licensePlate,
        },
    });
}
