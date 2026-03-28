import {onMounted, ref} from 'vue';
import {listBrands} from '../services/brandApi.js';

export function useBrandList() {
    const brandList = ref([]);
    const paginationBrand = ref({});
    const searchBrand = ref('');

    function loadBrandList(page = 1) {
        return listBrands({
            page,
            search: searchBrand.value,
        })
            .then((response) => {
                brandList.value = response.data.data;
                paginationBrand.value = response.data.meta;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    onMounted(() => loadBrandList());

    return {
        brandList,
        paginationBrand,
        searchBrand,
        loadBrandList,
    };
}

