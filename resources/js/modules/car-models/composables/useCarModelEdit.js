import { reactive, ref } from 'vue';
import { Modal } from 'bootstrap';
import { getModelCarDetailsByUuid, putModelCar } from '@modules/car-models/services/carModelApi.js';
import { mapCarModelApiError } from '@modules/car-models/errors/carModelApiErrorMapper.js';
import { useImagePreview } from '@shared/composables/useImagePreview.js';

export function useCarModelEdit({ onSuccess } = {}) {
    const runOnSuccess = onSuccess ?? (() => {});

    const editInfo = reactive({
        uuid: null,
        brandUuid: null,
        brandName: '',
        name: '',
        img_url: '',
        image: null,
        doorsNumber: null,
        seatsNumber: null,
        airbags: false,
        abs: false,
    });

    const alertsEditForm = ref([]);

    const {
        previewImage: previewEditImage,
        handleImage: handleImageEditForm,
        resetImage: resetPreviewImage,
    } = useImagePreview((file) => {
        editInfo.image = file;
    });

    function resetEditInfo() {
        editInfo.image = null;
        editInfo.brandName = '';
        editInfo.brandUuid = null;
        alertsEditForm.value = [];
        resetPreviewImage();
    }

    function closeModal() {
        const modal = document.querySelector('.modal.show');
        Modal.getInstance(modal)?.hide();
        resetEditInfo();
    }

    function getEditInfo(uuid) {
        return getModelCarDetailsByUuid(uuid)
            .then((response) => {
                const data = response.data.data;
                editInfo.uuid = data.uuid;
                editInfo.brandUuid = data.brandUuid;
                editInfo.brandName = data.brandName;
                editInfo.name = data.name;
                editInfo.img_url = '/storage/' + data.image;
                editInfo.doorsNumber = data.doorsNumber;
                editInfo.seatsNumber = data.seatsNumber;
                editInfo.airbags = data.airbags;
                editInfo.abs = data.abs;
                previewEditImage.value = editInfo.img_url;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    function submitUpdate(model = {}) {
        const formData = new FormData();
        formData.append('name', model.name);
        formData.append('brand_uuid', model.brandUuid ?? '');
        formData.append('doors_number', model.doorsNumber);
        formData.append('seats_number', model.seatsNumber);
        formData.append('airbags', model.airbags ? '1' : '0');
        formData.append('abs', model.abs ? '1' : '0');
        if (editInfo.image) {
            formData.append('image', editInfo.image);
        }

        return putModelCar(model.uuid, formData)
            .then(() => {
                closeModal();
                runOnSuccess();
            })
            .catch((error) => {
                console.error(error);
                alertsEditForm.value = mapCarModelApiError(error);
            });
    }

    return {
        editInfo,
        getEditInfo,
        submitUpdate,
        resetEditInfo,
        alertsEditForm,
        previewEditImage,
        handleImageEditForm,
    };
}
