import { useEntityDelete } from '@shared/composables/useEntityDelete.js';
import { deleteBrandByUuid, getBrandDetailsByUuid } from '@modules/brand/services/brandApi.js';

export function useBrandDelete({ onSuccess } = {}) {
    return useEntityDelete({
        fetchDetailsFn: getBrandDetailsByUuid,
        deleteFn: deleteBrandByUuid,
        onSuccess,
    });
}
