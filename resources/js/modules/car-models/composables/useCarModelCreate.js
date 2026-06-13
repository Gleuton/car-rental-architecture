import {onBeforeUnmount, reactive, ref} from 'vue';
import {Modal} from 'bootstrap';
import {createModelCar} from '@modules/car-models/services/carModelApi.js';
import {mapCarModelApiError} from '@modules/car-models/errors/carModelApiErrorMapper.js';

export function useCarModelCreate({onSuccess} = {}) {
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
    const fileCreateInput = ref(null);
    const previewCreateImage = ref(null);
    const isSubmittingCreateForm = ref(false);

    const runOnSuccess = onSuccess ?? (() => {
    });

    let previewObjectUrl = null;

    function cleanAlerts() {
        alertsCreateForm.value = [];
    }

    function revokePreviewImage() {
        if (previewObjectUrl) {
            URL.revokeObjectURL(previewObjectUrl);
            previewObjectUrl = null;
        }
    }

    function resetCreateForm() {
        createFormPayload.name = '';
        createFormPayload.image = null;
        createFormPayload.doors_number = 2;
        createFormPayload.seats_number = 4;
        createFormPayload.airbags = false;
        createFormPayload.abs = false;
        createFormPayload.brand_uuid = null;
        previewCreateImage.value = null;
        cleanAlerts();
        revokePreviewImage();

        if (fileCreateInput.value) {
            fileCreateInput.value.value = '';
        }
    }

    const handleCreateFormImage = (event) => {
        fileCreateInput.value = event.target;
        const file = fileCreateInput.value?.files?.[0];

        if (!file) {
            return;
        }

        createFormPayload.image = file;
        revokePreviewImage();
        previewObjectUrl = URL.createObjectURL(file);
        previewCreateImage.value = previewObjectUrl;
    };

    function closeModal() {
        const modalElement = document.getElementById('formCadModel');

        if (!modalElement) {
            return;
        }

        Modal.getOrCreateInstance(modalElement).hide();
    }

    async function submitCreateForm() {
        cleanAlerts();
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

            console.log(createFormPayload, formData);
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

    onBeforeUnmount(() => {
        revokePreviewImage();
    });

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
