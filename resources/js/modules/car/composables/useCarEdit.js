import { reactive, ref } from 'vue';
import { Modal } from 'bootstrap';
import { getCarDetailsByUuid, putCar } from '@modules/car/services/carApi.js';
import { mapCarApiError } from '@modules/car/errors/carApiErrorMapper.js';

export function useCarEdit({ onSuccess } = {}) {
    const runOnSuccess = onSuccess ?? (() => {});

    const editInfo = reactive({
        uuid: null,
        brandName: '',
        carModelName: '',
        licensePlate: '',
        color: '',
        isAvailable: true,
    });

    const alertsEditForm = ref([]);

    function resetEditInfo() {
        editInfo.uuid = null;
        editInfo.brandName = '';
        editInfo.carModelName = '';
        editInfo.licensePlate = '';
        editInfo.color = '';
        editInfo.isAvailable = true;
        alertsEditForm.value = [];
    }

    function closeModal() {
        const modal = document.querySelector('.modal.show');
        Modal.getInstance(modal)?.hide();
        resetEditInfo();
    }

    function getEditInfo(uuid) {
        return getCarDetailsByUuid(uuid)
            .then((response) => {
                const data = response.data.data;
                editInfo.uuid = data.uuid;
                editInfo.brandName = data.brandName;
                editInfo.carModelName = data.carModelName;
                editInfo.licensePlate = data.licensePlate;
                editInfo.color = data.color;
                editInfo.isAvailable = data.isAvailable;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    function submitUpdate(car = {}) {
        return putCar(car.uuid, {
            license_plate: car.licensePlate,
            color: car.color,
            is_available: car.isAvailable,
        })
            .then(() => {
                closeModal();
                runOnSuccess();
            })
            .catch((error) => {
                console.error(error);
                alertsEditForm.value = mapCarApiError(error);
            });
    }

    return {
        editInfo,
        getEditInfo,
        submitUpdate,
        resetEditInfo,
        alertsEditForm,
    };
}
