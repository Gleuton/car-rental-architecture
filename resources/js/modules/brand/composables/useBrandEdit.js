import {reactive} from "vue";
import {getBrandDetails} from "@modules/brand/services/brandApi.js";

export default function useBrandEdit() {
    const editInfo = reactive({
        id: null,
        name: '',
        img_url: '',
    });

    function getEditInfo(id) {
        return getBrandDetails(id)
            .then((response) => {
                editInfo.id = response.data.data.id;
                editInfo.name = response.data.data.name;
                editInfo.img_url = '/storage/' + response.data.data.image;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    return {
        editInfo,
        getEditInfo
    }
}
