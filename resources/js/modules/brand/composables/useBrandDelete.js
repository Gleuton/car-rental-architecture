import {reactive} from 'vue';
import {deleteBrand, getBrandDetails} from '@modules/brand/services/brandApi.js';
import {Modal} from "bootstrap";

export default function useBrandDelete({onSuccess} = {}) {
    const runOnSuccess = onSuccess ?? (() => {});
    const deleteInfo = reactive({
        id: null,
        name: '',
    });

    function resetDeleteInfo() {
        deleteInfo.id = null;
        deleteInfo.name = '';
    }

    function closeModal() {
        const modal = document.querySelector('.modal.show');
        Modal.getInstance(modal)?.hide();
    }

    function getDeleteInfo(id) {
        return getBrandDetails(id)
            .then((response) => {
                deleteInfo.id = response.data.data.id;
                deleteInfo.name = response.data.data.name;
                console.log(deleteInfo);
            })
            .catch((error) => {
                console.error(error);
            });
    }

    function deleteSubmit(id) {
        return deleteBrand(id)
            .then(() => {
                runOnSuccess();
                closeModal();
                resetDeleteInfo();
            })
            .catch((error) => {
                console.error(error);
            })
    }

    return {
        deleteInfo,
        resetDeleteInfo,
        deleteSubmit,
        getDeleteInfo,
    };
}
