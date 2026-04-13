import {reactive} from 'vue';
import {getBrandDetails} from '@modules/brand/services/brandApi.js';

export function useBrandDetails() {
    const detailsBrand = reactive({
        id: null,
        name: '',
        image: null,
        img_url: '',
    });

    function resetDetailsInfo() {
        detailsBrand.id = null;
        detailsBrand.name = '';
        detailsBrand.image = null;
        detailsBrand.img_url = '';
    }

    function getDetailsInfo(id) {
        resetDetailsInfo();

        return getBrandDetails(id)
            .then((response) => {
                detailsBrand.id = response.data.data.id;
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
