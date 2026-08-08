import { mapApiError } from '@shared/errors/apiErrorMapper.js';

const CAR_CODE_MESSAGES = {
    INVALID_LICENSE_PLATE: 'A placa do carro e obrigatória.',
    LICENSE_PLATE_TOO_SHORT: 'A placa deve ter pelo menos 7 caracteres.',
    LICENSE_PLATE_TOO_LONG: 'A placa deve ter no máximo 10 caracteres.',
    INVALID_COLOR: 'A cor do carro e obrigatória.',
    COLOR_TOO_SHORT: 'A cor deve ter pelo menos 3 caracteres.',
    COLOR_TOO_LONG: 'A cor deve ter no máximo 50 caracteres.',
    INVALID_KM: 'A quilometragem deve ser zero ou positiva.',
    NOT_FOUND: 'Carro não encontrado.',
    ALREADY_EXISTS: 'Já existe um carro com essa placa.',
    MODEL_NOT_FOUND: 'Modelo do carro não encontrado.',
};

const CAR_APP_CODE_MESSAGES = {
    6001: CAR_CODE_MESSAGES.INVALID_LICENSE_PLATE,
    6002: CAR_CODE_MESSAGES.LICENSE_PLATE_TOO_SHORT,
    6003: CAR_CODE_MESSAGES.LICENSE_PLATE_TOO_LONG,
    6004: CAR_CODE_MESSAGES.INVALID_COLOR,
    6005: CAR_CODE_MESSAGES.COLOR_TOO_SHORT,
    6006: CAR_CODE_MESSAGES.COLOR_TOO_LONG,
    6007: CAR_CODE_MESSAGES.INVALID_KM,
    6008: CAR_CODE_MESSAGES.NOT_FOUND,
    6009: CAR_CODE_MESSAGES.ALREADY_EXISTS,
    6010: CAR_CODE_MESSAGES.MODEL_NOT_FOUND,
};

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
