import { reactive } from 'vue';
import { Modal } from 'bootstrap';
import { deleteCarByUuid, getCarDetailsByUuid } from '@modules/car/services/carApi.js';

export function useCarDelete({ onSuccess } = {}) {
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
        return getCarDetailsByUuid(uuid)
            .then((response) => {
                deleteInfo.uuid = response.data.data.uuid;
                deleteInfo.name = response.data.data.licensePlate;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    function deleteSubmit(uuid) {
        return deleteCarByUuid(uuid)
            .then(() => {
                runOnSuccess();
                closeModal();
                resetDeleteInfo();
            })
            .catch((error) => {
                console.error(error);
            });
    }

    return { deleteInfo, getDeleteInfo, deleteSubmit, resetDeleteInfo };
}
