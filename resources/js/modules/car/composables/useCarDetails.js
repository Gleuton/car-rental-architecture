import { reactive } from 'vue';
import { getCarDetailsByUuid } from '@modules/car/services/carApi.js';

export function useCarDetails() {
    const detailsCar = reactive({
        uuid: null,
        brandName: '',
        carModelName: '',
        licensePlate: '',
        color: '',
        isAvailable: null,
        km: null,
    });

    function resetDetailsInfo() {
        detailsCar.uuid = null;
        detailsCar.brandName = '';
        detailsCar.carModelName = '';
        detailsCar.licensePlate = '';
        detailsCar.color = '';
        detailsCar.isAvailable = null;
        detailsCar.km = null;
    }

    function getDetailsInfo(uuid) {
        resetDetailsInfo();

        return getCarDetailsByUuid(uuid)
            .then((response) => {
                const data = response.data.data;
                detailsCar.uuid = data.uuid;
                detailsCar.brandName = data.brandName;
                detailsCar.carModelName = data.carModelName;
                detailsCar.licensePlate = data.licensePlate;
                detailsCar.color = data.color;
                detailsCar.isAvailable = data.isAvailable;
                detailsCar.km = data.km;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    return {
        detailsCar,
        getDetailsInfo,
        resetDetailsInfo,
    };
}
