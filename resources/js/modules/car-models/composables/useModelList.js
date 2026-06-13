import {onMounted, ref} from 'vue';
import {listModelCar} from '@modules/car-models/services/carModelApi.js';

export function useModelList() {
    const modelList = ref([]);
    const paginationModel = ref({});
    const currentSearch = ref('');

    function loadModelList(page = 1, search = '') {
        currentSearch.value = search;

        return listModelCar({
            page,
            search,
        })
            .then((response) => {
                modelList.value = response.data.data;
                paginationModel.value = response.data.meta;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    onMounted(() => loadModelList());

    return {
        modelList,
        paginationModel,
        loadModelList,
        currentSearch,
    };
}

