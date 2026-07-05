import { reactive, ref } from 'vue';
import {mapCarApiError} from "@modules/car/errors/carModelApiErrorMapper.js";

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

    async function submitCreateForm() {
        alertsCreateForm.value = [];
        isSubmittingCreateForm.value = true;

        try {

        } catch (error) {
            alertsCreateForm.value = mapCarApiError(error);
        } finally {
            isSubmittingCreateForm.value = false;
        }
    }

    function resetCreateForm() {
        createFormPayload.car_model_uuid = null;
        createFormPayload.license_plate = '';
        createFormPayload.color = '';
        createFormPayload.is_available = true;
        createFormPayload.km = 0;
        alertsCreateForm.value = [];
    }


    return {
        createFormPayload,
        submitCreateForm,
        isSubmittingCreateForm,
        resetCreateForm,
        alertsCreateForm,
    };
}
