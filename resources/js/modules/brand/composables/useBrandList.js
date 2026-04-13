import {onMounted, ref} from 'vue';
import {listBrands} from '@modules/brand/services/brandApi.js';

export function useBrandList() {
    const brandList = ref([]);
    const paginationBrand = ref({});

    function loadBrandList(page = 1, search = '') {
        return listBrands({
            page,
            search: search,
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
        loadBrandList,
    };
}

