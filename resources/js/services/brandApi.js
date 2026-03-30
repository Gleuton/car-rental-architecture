import axios from 'axios';

function getAuthConfig() {
    const token = localStorage.getItem('token');

    return {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    };
}

export function createBrand(formData) {
    return axios.post('/api/brands', formData, getAuthConfig());
}

export function listBrands({page = 1, search = ''} = {}) {
    return axios.get('/api/brands', {
        ...getAuthConfig(),
        params: {
            page,
            search,
        },
    });
}

export function getBrandDetails(id) {
    return axios.get(`/api/brands/${id}`, getAuthConfig());
}

export function deleteBrand(id) {
    return axios.delete(`/api/brands/${id}`, getAuthConfig());
}

