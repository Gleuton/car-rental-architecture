import {onBeforeUnmount, reactive, ref} from 'vue';
import {Modal} from 'bootstrap';
import {createBrand} from '@modules/brand/services/brandApi.js';
import {mapBrandApiError} from '@modules/brand/errors/brandApiErrorMapper.js';

export function useBrandCreate({onSuccess} = {}) {
    const createFormPayload = reactive({
        name: '',
        image: null,
    });

    const alertsCreateForm = ref([]);
    const fileCreateInput = ref(null);
    const previewCreateImage = ref(null);
    const isSubmittingCreateForm = ref(false);

    const runOnSuccess = onSuccess ?? (() => {});

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
        const modalElement = document.getElementById('formCadBrand');

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
