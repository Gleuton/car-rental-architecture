import { reactive, ref } from 'vue';
import { Modal } from 'bootstrap';
import { createBrand } from '@modules/brand/services/brandApi.js';
import { mapBrandApiError } from '@modules/brand/errors/brandApiErrorMapper.js';
import { useImagePreview } from '@shared/composables/useImagePreview.js';

export function useBrandCreate({ onSuccess } = {}) {
    const createFormPayload = reactive({
        name: '',
        image: null,
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
        alertsCreateForm.value = [];
        resetPreviewImage();
    }

    function closeModal() {
        const modalElement = document.getElementById('formCadBrand');
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

            await createBrand(formData);
            resetCreateForm();
            closeModal();
            runOnSuccess();
        } catch (error) {
            alertsCreateForm.value = mapBrandApiError(error);
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
