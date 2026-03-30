import {onMounted, ref} from 'vue';
import {getBrandDetails, listBrands} from '../services/brandApi.js';

export function useBrandList() {
    const brandList = ref([]);
    const paginationBrand = ref({});
    const searchBrand = ref('');
    const detailsBrand = ref(null);

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

    function getDetailsBrand(id) {
        return getBrandDetails(id)
            .then((response) => {
                detailsBrand.value = response.data.data;
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
        detailsBrand,
        loadBrandList,
        getDetailsBrand
    };
}

