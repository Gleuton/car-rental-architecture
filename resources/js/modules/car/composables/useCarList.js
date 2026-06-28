import {onMounted, ref} from "vue";
import {listCars} from '@modules/car/services/carApi.js';

export function useCarList() {
    const carList = ref([]);

    function loadCarList(page = 1, licensePlate = '') {
        return listCars({
            page,
            licensePlate,
        }).then((response) => {
            carList.value = response.data.data;
        }).catch((error) => {
            console.error(error);
        });
    }

    onMounted(() => loadCarList());

    return {
        carList,
        loadCarList,
    };
}
