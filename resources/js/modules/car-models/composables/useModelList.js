import { useEntityList } from '@shared/composables/useEntityList.js';
import { listModelCar } from '@modules/car-models/services/carModelApi.js';

export function useModelList() {
    const { items: modelList, pagination: paginationModel, currentSearch, loadList } = useEntityList(listModelCar);

    function loadModelList(page = 1, search = '', brandUuid = '') {
        return loadList(page, search, { brandUuid });
    }

    return { modelList, paginationModel, loadModelList, currentSearch };
}
