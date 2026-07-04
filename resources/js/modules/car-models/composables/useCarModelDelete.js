import { useEntityDelete } from '@shared/composables/useEntityDelete.js';
import { deleteModelCarByUuid, getModelCarDetailsByUuid } from '@modules/car-models/services/carModelApi.js';

export function useCarModelDelete({ onSuccess } = {}) {
    return useEntityDelete({
        fetchDetailsFn: getModelCarDetailsByUuid,
        deleteFn: deleteModelCarByUuid,
        onSuccess,
    });
}
