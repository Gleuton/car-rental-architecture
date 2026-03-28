const STATUS_MESSAGES = {
    401: 'Sua sessão expirou. Faca login novamente.',
    403: 'Voce nao tem permissão para realizar esta ação.',
    404: 'Recurso nao encontrado.',
    500: 'Erro interno do servidor. Tente novamente em instantes.',
};

const FIELD_LABELS = {
    name: 'Nome',
    image: 'Imagem',
};

const VALIDATION_KEY_MESSAGES = {
    'validation.required': 'Este campo e obrigatório.',
    'validation.string': 'Este campo deve ser um texto válido.',
    'validation.image': 'O arquivo enviado deve ser uma imagem válida.',
    'validation.mimes': 'O tipo do arquivo enviado não e permitido.',
    'validation.max.string': 'O texto informado e maior do que o permitido.',
    'validation.max.file': 'O arquivo excede o tamanho máximo permitido.',
};

function translateValidationMessage(message) {
    if (typeof message !== 'string') {
        return 'Valor inválido.';
    }

    if (VALIDATION_KEY_MESSAGES[message]) {
        return VALIDATION_KEY_MESSAGES[message];
    }

    if (message.startsWith('validation.')) {
        return 'Valor inválido para este campo.';
    }

    return message;
}

function normalizeValidationErrors(errors) {
    if (!errors || typeof errors !== 'object') {
        return ['Dados inválidos.'];
    }

    return Object.entries(errors).flatMap(([field, msgs]) => {
        const label = FIELD_LABELS[field] || field;
        const list = Array.isArray(msgs) ? msgs : [msgs];

        return list.map((msg) => `${label}: ${translateValidationMessage(msg)}`);
    });
}

export function mapApiError(error, options = {}) {
    const { domainErrorMapper } = options;
    const response = error?.response;

    if (!response) {
        return ['Falha de conexão. Verifique sua internet e tente novamente.'];
    }

    const { status, data } = response;

    if (status === 422) {
        return normalizeValidationErrors(data?.errors);
    }

    if (status === 409 && data?.type === 'DOMAIN_ERROR') {
        if (typeof domainErrorMapper === 'function') {
            const domainMessages = domainErrorMapper(data);

            if (domainMessages) {
                return domainMessages;
            }
        }

        return [data?.message || 'Conflito de regra de negocio.'];
    }

    if (STATUS_MESSAGES[status]) {
        return [STATUS_MESSAGES[status]];
    }

    if (data?.message) {
        return [data.message];
    }

    return ['Ocorreu um erro inesperado.'];
}
