import {reactive, ref} from 'vue';
import {createBrand, deleteBrand} from "../services/brandApi.js";
import {mapBrandApiError} from "../errors/brandApiErrorMapper.js";
import {Modal} from "bootstrap";

export function useBrandForm({onSuccess} = {}) {
    const formPayload = reactive({
        name: '',
        image: null,
    });

    const runOnSuccess = onSuccess ?? (() => {});

    const alerts = ref([]);
    const success = ref(false);
    const fileInput = ref(null);

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
                success.value = true;
                resetForm();
                runOnSuccess();
            })
            .catch((error) => {
                alerts.value = mapBrandApiError(error);
            });
    }

    function deleteForm(id) {
        return deleteBrand(id)
            .then(() => {
                closeModal();
                runOnSuccess();
            })
            .catch((error) => {
                console.error(error);
            })
    }

    function closeModal() {
        const modal = document.querySelector('.modal.show');
        Modal.getInstance(modal)?.hide();
    }

    return {
        alerts,
        success,
        formPayload,
        fileInput,
        deleteForm,
        submitForm,
        resetForm,
    };
}
