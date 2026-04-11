import {reactive, ref} from "vue";
import {getBrandDetails, putBrand} from "@modules/brand/services/brandApi.js";
import {mapBrandApiError} from "@modules/brand/errors/brandApiErrorMapper.js";
import {Modal} from "bootstrap";

export default function useBrandEdit({onSuccess} = {}) {
    const runOnSuccess = onSuccess ?? (() => {});
    const editInfo = reactive({
        id: null,
        name: '',
        img_url: '',
        image: null,
    });

    const previewEditImage = ref(null);
    const alertsEditForm = ref([]);
    const fileEditInput = ref(null);

    function resetEditForm() {
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

        return putBrand(brand.id, formData).then(() => {
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
        resetEditForm();
    }

    function getEditInfo(id) {
        return getBrandDetails(id)
            .then((response) => {
                editInfo.id = response.data.data.id;
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
        resetEditForm,
        alertsEditForm,
        previewEditImage,
        handleImageEditForm,
    }
}
