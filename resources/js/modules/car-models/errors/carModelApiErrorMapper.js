import { mapApiError } from '@shared/errors/apiErrorMapper.js';

const CAR_MODEL_CODE_MESSAGES = {

};

const CAR_MODEL_APP_CODE_MESSAGES = {

};

export function mapCarModelDomainError(data) {
    if (!data || data.domain !== 'car-model') {
        return null;
    }

    if (data.code && CAR_MODEL_CODE_MESSAGES[data.code]) {
        return [CAR_MODEL_CODE_MESSAGES[data.code]];
    }

    if (Number.isInteger(data.app_code) && CAR_MODEL_APP_CODE_MESSAGES[data.app_code]) {
        return [CAR_MODEL_APP_CODE_MESSAGES[data.app_code]];
    }

    return ['Erro de regra de negocio do Modelo do carro.'];
}

export function mapCarModelApiError(error) {
    return mapApiError(error, {
        domainErrorMapper: mapCarModelDomainError,
    });
}
