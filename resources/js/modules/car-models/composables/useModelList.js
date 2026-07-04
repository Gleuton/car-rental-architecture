import { useEntityList } from '@shared/composables/useEntityList.js';
import { listModelCar } from '@modules/car-models/services/carModelApi.js';

export function useModelList() {
    const { items: modelList, pagination: paginationModel, currentSearch, loadList: loadModelList } = useEntityList(listModelCar);
    return { modelList, paginationModel, loadModelList, currentSearch };
}
