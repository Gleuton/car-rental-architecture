import {reactive, ref} from "vue";
import {getBrandDetailsByUuid, putBrand} from "@modules/brand/services/brandApi.js";
import {mapBrandApiError} from "@modules/brand/errors/brandApiErrorMapper.js";
import {Modal} from "bootstrap";

export function useBrandEdit({onSuccess} = {}) {
    const runOnSuccess = onSuccess ?? (() => {});
    const editInfo = reactive({
        uuid: null,
        name: '',
        img_url: '',
        image: null,
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
    }

    function submitUpdate(brand = {}) {
        const formData = new FormData();
        formData.append('name', brand.name);

        if (editInfo.image) {
            formData.append('image', editInfo.image);
        }

        return putBrand(brand.uuid, formData).then(() => {
            closeModal();
            runOnSuccess();
        }).catch((error) => {
            console.error(error);
            alertsEditForm.value = mapBrandApiError(error);
        });
    }

    function closeModal() {
        const modal = document.querySelector('.modal.show');
        Modal.getInstance(modal)?.hide();
        resetEditInfo();
    }

    function getEditInfo(uuid) {
        return getBrandDetailsByUuid(uuid)
            .then((response) => {
                editInfo.uuid = response.data.data.uuid;
                editInfo.name = response.data.data.name;
                editInfo.img_url = '/storage/' + response.data.data.image;
                previewEditImage.value = editInfo.img_url;
            })
            .catch((error) => {
                console.error(error);
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
    }
}
