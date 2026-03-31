import { mapApiError } from './apiErrorMapper.js';

const BRAND_CODE_MESSAGES = {
    ALREADY_EXISTS: 'Já existe uma marca com esse nome.',
    INVALID_NAME: 'O nome da marca e obrigatório.',
    NAME_TOO_SHORT: 'O nome da marca precisa ter pelo menos 3 caracteres.',
    NAME_TOO_LONG: 'O nome da marca deve ter no máximo 120 caracteres.',
    NOT_FOUND: 'Marca não encontrada.',
};

const BRAND_APP_CODE_MESSAGES = {
    4001: BRAND_CODE_MESSAGES.ALREADY_EXISTS,
    4002: BRAND_CODE_MESSAGES.INVALID_NAME,
    4003: BRAND_CODE_MESSAGES.NAME_TOO_SHORT,
    4004: BRAND_CODE_MESSAGES.NAME_TOO_LONG,
    4005: BRAND_CODE_MESSAGES.NOT_FOUND,
};

export function mapBrandDomainError(data) {
    if (!data || data.domain !== 'brand') {
        return null;
    }

    if (data.code && BRAND_CODE_MESSAGES[data.code]) {
        return [BRAND_CODE_MESSAGES[data.code]];
    }

    if (Number.isInteger(data.app_code) && BRAND_APP_CODE_MESSAGES[data.app_code]) {
        return [BRAND_APP_CODE_MESSAGES[data.app_code]];
    }

    return ['Erro de regra de negocio da marca.'];
}

export function mapBrandApiError(error) {
    return mapApiError(error, {
        domainErrorMapper: mapBrandDomainError,
    });
}
