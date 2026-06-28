import {reactive} from 'vue';
import {Modal} from 'bootstrap';
import {deleteModelCarByUuid, getModelCarDetailsByUuid} from '@modules/car-models/services/carModelApi.js';

export function useCarModelDelete({onSuccess} = {}) {
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
        return getModelCarDetailsByUuid(uuid)
            .then((response) => {
                deleteInfo.uuid = response.data.data.uuid;
                deleteInfo.name = response.data.data.name;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    function deleteSubmit(uuid) {
        return deleteModelCarByUuid(uuid)
            .then(() => {
                runOnSuccess();
                closeModal();
                resetDeleteInfo();
            })
            .catch((error) => {
                console.error(error);
            });
    }

    return {
        deleteInfo,
        getDeleteInfo,
        deleteSubmit,
        resetDeleteInfo,
    };
}
