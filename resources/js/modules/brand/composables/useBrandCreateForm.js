import {onBeforeUnmount, reactive, ref} from 'vue';
import {Modal} from 'bootstrap';
import {createBrand} from '@modules/brand/services/brandApi.js';
import {mapBrandApiError} from '@modules/brand/errors/brandApiErrorMapper.js';

export function useBrandCreateForm({onSuccess, modalId = 'formCadBrand'} = {}) {
    const formPayload = reactive({
        name: '',
        image: null,
    });

    const alerts = ref([]);
    const fileInput = ref(null);
    const previewImage = ref(null);
    const isSubmitting = ref(false);

    const runOnSuccess = onSuccess ?? (() => {});

    let previewObjectUrl = null;

    function cleanAlerts() {
        alerts.value = [];
    }

    function revokePreviewImage() {
        if (previewObjectUrl) {
            URL.revokeObjectURL(previewObjectUrl);
            previewObjectUrl = null;
        }
    }

    function resetForm() {
        formPayload.name = '';
        formPayload.image = null;
        previewImage.value = null;
        cleanAlerts();
        revokePreviewImage();

        if (fileInput.value) {
            fileInput.value.value = '';
        }
    }

    const handleImage = (event) => {
        fileInput.value = event.target;
        const file = fileInput.value?.files?.[0];

        if (!file) {
            return;
        }

        formPayload.image = file;
        revokePreviewImage();
        previewObjectUrl = URL.createObjectURL(file);
        previewImage.value = previewObjectUrl;
    };

    function closeModal() {
        const modalElement = document.getElementById(modalId);

        if (!modalElement) {
            return;
        }

        Modal.getOrCreateInstance(modalElement).hide();
    }

    async function submitForm() {
        cleanAlerts();
        isSubmitting.value = true;

        try {
            const formData = new FormData();

            formData.append('name', formPayload.name);

            if (formPayload.image) {
                formData.append('image', formPayload.image);
            }

            await createBrand(formData);
            resetForm();
            closeModal();
            runOnSuccess();
        } catch (error) {
            alerts.value = mapBrandApiError(error);
        } finally {
            isSubmitting.value = false;
        }
    }

    onBeforeUnmount(() => {
        revokePreviewImage();
    });

    return {
        alerts,
        formPayload,
        previewImage,
        handleImage,
        resetForm,
        submitForm,
        isSubmitting,
    };
}
