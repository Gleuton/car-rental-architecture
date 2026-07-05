import { mapApiError } from '@shared/errors/apiErrorMapper.js';

const CAR_CODE_MESSAGES = {};

const CAR_APP_CODE_MESSAGES = {};

export function mapCarDomainError(data) {
    if (!data || data.domain !== 'car') {
        return null;
    }

    if (data.code && CAR_CODE_MESSAGES[data.code]) {
        return [CAR_CODE_MESSAGES[data.code]];
    }

    if (Number.isInteger(data.app_code) && CAR_APP_CODE_MESSAGES[data.app_code]) {
        return [CAR_APP_CODE_MESSAGES[data.app_code]];
    }

    return ['Erro de regra de negocio do carro.'];
}

export function mapCarApiError(error) {
    return mapApiError(error, {
        domainErrorMapper: mapCarDomainError,
    });
}
