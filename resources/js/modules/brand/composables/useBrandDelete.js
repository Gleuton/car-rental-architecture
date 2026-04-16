import {reactive} from 'vue';
import {deleteBrandByUuid, getBrandDetailsByUuid} from '@modules/brand/services/brandApi.js';
import {Modal} from "bootstrap";

export function useBrandDelete({onSuccess} = {}) {
    const runOnSuccess = onSuccess ?? (() => {});
    const deleteInfo = reactive({
        uuid: null,
        name: '',
    });

    function resetDeleteInfo() {
        deleteInfo.uuid = null;
        deleteInfo.name = '';
    }

    function closeModal() {
        const modal = document.querySelector('.modal.show');
        Modal.getInstance(modal)?.hide();
    }

    function getDeleteInfo(uuid) {
        return getBrandDetailsByUuid(uuid)
            .then((response) => {
                deleteInfo.uuid = response.data.data.uuid;
                deleteInfo.name = response.data.data.name;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    function deleteSubmit(uuid) {
        return deleteBrandByUuid(uuid)
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
