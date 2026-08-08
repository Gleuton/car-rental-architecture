import { useEntityList } from '@shared/composables/useEntityList.js';
import { listCars } from '@modules/car/services/carApi.js';

export function useCarList() {
    const { items: carList, pagination: paginationCar, currentSearch, loadList: loadCarList } = useEntityList(listCars);

    return { carList, paginationCar, currentSearch, loadCarList };
}
