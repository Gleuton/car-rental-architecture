import {reactive} from 'vue';
import {getBrandDetailsByUuid} from '@modules/brand/services/brandApi.js';

export function useBrandDetails() {
    const detailsBrand = reactive({
        uuid: null,
        name: '',
        image: null,
        img_url: '',
    });

    function resetDetailsInfo() {
        detailsBrand.uuid = null;
        detailsBrand.name = '';
        detailsBrand.image = null;
        detailsBrand.img_url = '';
    }

    function getDetailsInfo(uuid) {
        resetDetailsInfo();

        return getBrandDetailsByUuid(uuid)
            .then((response) => {
                detailsBrand.uuid = response.data.data.uuid;
                detailsBrand.name = response.data.data.name;
                detailsBrand.image = response.data.data.image;
                detailsBrand.img_url = '/storage/' + response.data.data.image;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    return {
        detailsBrand,
        getDetailsInfo,
        resetDetailsInfo,
    };
}
