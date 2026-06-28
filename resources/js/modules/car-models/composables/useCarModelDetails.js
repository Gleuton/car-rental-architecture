import {reactive} from 'vue';
import {getModelCarDetailsByUuid} from '@modules/car-models/services/carModelApi.js';

export function useCarModelDetails() {
    const detailsModel = reactive({
        uuid: null,
        brandUuid: null,
        name: '',
        image: null,
        img_url: '',
        doorsNumber: null,
        seatsNumber: null,
        airbags: null,
        abs: null,
    });

    function resetDetailsInfo() {
        detailsModel.uuid = null;
        detailsModel.brandUuid = null;
        detailsModel.name = '';
        detailsModel.image = null;
        detailsModel.img_url = '';
        detailsModel.doorsNumber = null;
        detailsModel.seatsNumber = null;
        detailsModel.airbags = null;
        detailsModel.abs = null;
    }

    function getDetailsInfo(uuid) {
        resetDetailsInfo();

        return getModelCarDetailsByUuid(uuid)
            .then((response) => {
                const data = response.data.data;
                detailsModel.uuid = data.uuid;
                detailsModel.brandUuid = data.brandUuid;
                detailsModel.name = data.name;
                detailsModel.image = data.image;
                detailsModel.img_url = '/storage/' + data.image;
                detailsModel.doorsNumber = data.doorsNumber;
                detailsModel.seatsNumber = data.seatsNumber;
                detailsModel.airbags = data.airbags;
                detailsModel.abs = data.abs;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    return {
        detailsModel,
        getDetailsInfo,
        resetDetailsInfo,
    };
}
