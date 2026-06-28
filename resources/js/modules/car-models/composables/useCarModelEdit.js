import {reactive, ref} from 'vue';
import {Modal} from 'bootstrap';
import {getModelCarDetailsByUuid, putModelCar} from '@modules/car-models/services/carModelApi.js';
import {mapCarModelApiError} from '@modules/car-models/errors/carModelApiErrorMapper.js';

export function useCarModelEdit({onSuccess} = {}) {
    const runOnSuccess = onSuccess ?? (() => {});

    const editInfo = reactive({
        uuid: null,
        brandUuid: null,
        name: '',
        img_url: '',
        image: null,
        doorsNumber: null,
        seatsNumber: null,
        airbags: false,
        abs: false,
    });

    const previewEditImage = ref(null);
    const alertsEditForm = ref([]);
    const fileEditInput = ref(null);

    function resetEditInfo() {
        editInfo.image = null;
        previewEditImage.value = null;
        alertsEditForm.value = [];

        if (fileEditInput.value) {
            fileEditInput.value.value = '';
        }
    }

    const handleImageEditForm = (event) => {
        fileEditInput.value = event.target;
        const file = fileEditInput.value.files[0];
        if (file) {
            editInfo.image = file;
            previewEditImage.value = URL.createObjectURL(file);
        }
    };

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
        formData.append('brand_uuid', model.brandUuid);
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
