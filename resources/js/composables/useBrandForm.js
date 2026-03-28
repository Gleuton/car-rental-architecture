import {reactive, ref} from 'vue';
import {createBrand} from "../services/brandApi.js";
import {mapBrandApiError} from "../errors/brandApiErrorMapper.js";

export function useBrandForm() {
    const formPayload = reactive({
        name: '',
        image: null,
    });

    const alerts = ref([]);
    const success = ref(false);
    const fileInput = ref(null);
    fileInput.value = null;

    function cleanAlerts() {
        alerts.value = [];
        success.value = false;
    }

    function resetForm() {
        formPayload.name = '';
        formPayload.image = null;
        cleanAlerts();

        if (fileInput.value) {
            fileInput.value.value = '';
        }
    }

    function submitForm() {
        cleanAlerts();
        const formData = new FormData();

        formData.append('name', formPayload.name);
        if (formPayload.image) {
            formData.append('image', formPayload.image);
        }

        createBrand(formData)
            .then(() => {
                resetForm();
                success.value = true;
            })
            .catch((error) => {
                alerts.value = mapBrandApiError(error);
            });
    }

    return {
        alerts,
        success,
        formPayload,
        fileInput,
        submitForm,
        resetForm,
    };
}
