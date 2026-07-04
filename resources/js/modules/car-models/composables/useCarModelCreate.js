import { reactive, ref } from 'vue';
import { Modal } from 'bootstrap';
import { createModelCar } from '@modules/car-models/services/carModelApi.js';
import { mapCarModelApiError } from '@modules/car-models/errors/carModelApiErrorMapper.js';
import { useImagePreview } from '@shared/composables/useImagePreview.js';

export function useCarModelCreate({ onSuccess } = {}) {
    const createFormPayload = reactive({
        name: '',
        image: null,
        doors_number: 2,
        seats_number: 4,
        airbags: false,
        abs: false,
        brand_uuid: null,
    });

    const alertsCreateForm = ref([]);
    const isSubmittingCreateForm = ref(false);
    const runOnSuccess = onSuccess ?? (() => {});

    const {
        previewImage: previewCreateImage,
        handleImage: handleCreateFormImage,
        resetImage: resetPreviewImage,
    } = useImagePreview((file) => {
        createFormPayload.image = file;
    });

    function resetCreateForm() {
        createFormPayload.name = '';
        createFormPayload.image = null;
        createFormPayload.doors_number = 2;
        createFormPayload.seats_number = 4;
        createFormPayload.airbags = false;
        createFormPayload.abs = false;
        createFormPayload.brand_uuid = null;
        alertsCreateForm.value = [];
        resetPreviewImage();
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
            const formData = new FormData();
            formData.append('name', createFormPayload.name);
            if (createFormPayload.image) {
                formData.append('image', createFormPayload.image);
            }
            formData.append('doors_number', createFormPayload.doors_number);
            formData.append('seats_number', createFormPayload.seats_number);
            formData.append('airbags', createFormPayload.airbags ? '1' : '0');
            formData.append('abs', createFormPayload.abs ? '1' : '0');
            if (createFormPayload.brand_uuid) {
                formData.append('brand_uuid', createFormPayload.brand_uuid);
            }

            await createModelCar(formData);
            resetCreateForm();
            closeModal();
            runOnSuccess();
        } catch (error) {
            alertsCreateForm.value = mapCarModelApiError(error);
        } finally {
            isSubmittingCreateForm.value = false;
        }
    }

    return {
        alertsCreateForm,
        createFormPayload,
        previewCreateImage,
        handleCreateFormImage,
        resetCreateForm,
        submitCreateForm,
        isSubmittingCreateForm,
    };
}
