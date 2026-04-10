import {reactive, ref} from 'vue';
import {deleteBrand, putBrand} from '@modules/brand/services/brandApi.js';
import {mapBrandApiError} from '@modules/brand/errors/brandApiErrorMapper.js';
import {Modal} from 'bootstrap';

export function useBrandForm({onSuccess} = {}) {
    const formPayload = reactive({
        image: null,
    });

    const runOnSuccess = onSuccess ?? (() => {});

    const alerts = ref([]);
    const fileInput = ref(null);
    const previewImage = ref(null);


    function cleanAlerts() {
        alerts.value = [];
    }

    function resetForm() {
        formPayload.image = null;
        previewImage.value = null;
        cleanAlerts();

        if (fileInput.value) {
            fileInput.value.value = '';
        }
    }

    const handleImage = (event) => {
        fileInput.value = event.target;
        const file = fileInput.value.files[0];
        if (file) {
            formPayload.image = file;
            previewImage.value = URL.createObjectURL(file);
        }
    }

    function deleteForm(id) {
        return deleteBrand(id)
            .then(() => {
                resetForm();
                closeModal();
                runOnSuccess();
            })
            .catch((error) => {
                alerts.value = mapBrandApiError(error);
            })
    }

    function updateForm(brand = {}) {
        const formData = new FormData();
        formData.append('name', brand.name);

        if (formPayload.image) {
            formData.append('image', formPayload.image);
        }

        return putBrand(brand.id, formData).then(() => {
            closeModal();
            runOnSuccess();
        }).catch((error) => {
            alerts.value = mapBrandApiError(error);
        });
    }

    function closeModal() {
        const modal = document.querySelector('.modal.show');
        Modal.getInstance(modal)?.hide();
    }

    return {
        alerts,
        formPayload,
        previewImage,
        handleImage,
        deleteForm,
        updateForm,
        resetForm,
    };
}
