import { reactive, ref } from 'vue';
import { Modal } from 'bootstrap';
import { createCar } from '@modules/car/services/carApi.js';
import { mapCarApiError } from '@modules/car/errors/carModelApiErrorMapper.js';

export function useCarCreate({ onSuccess } = {}) {
    const createFormPayload = reactive({
        car_model_uuid: null,
        license_plate: '',
        color: '',
        is_available: true,
        km: 0,
    });

    const alertsCreateForm = ref([]);
    const isSubmittingCreateForm = ref(false);
    const runOnSuccess = onSuccess ?? (() => {});

    function resetCreateForm() {
        createFormPayload.car_model_uuid = null;
        createFormPayload.license_plate = '';
        createFormPayload.color = '';
        createFormPayload.is_available = true;
        createFormPayload.km = 0;
        alertsCreateForm.value = [];
    }

    function closeModal() {
        const modalElement = document.getElementById('formCadModel');
        if (!modalElement) return;
        Modal.getOrCreateInstance(modalElement).hide();
    }

    async function submitCreateForm() {
        alertsCreateForm.value = [];
        isSubmittingCreateForm.value = true;

        try {
            await createCar({
                car_model_uuid: createFormPayload.car_model_uuid,
                license_plate: createFormPayload.license_plate,
                color: createFormPayload.color,
                is_available: createFormPayload.is_available,
                km: createFormPayload.km,
            });
            resetCreateForm();
            closeModal();
            runOnSuccess();
        } catch (error) {
            alertsCreateForm.value = mapCarApiError(error);
        } finally {
            isSubmittingCreateForm.value = false;
        }
    }

    return {
        createFormPayload,
        submitCreateForm,
        isSubmittingCreateForm,
        resetCreateForm,
        alertsCreateForm,
    };
}
