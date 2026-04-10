import {onMounted, reactive, ref} from 'vue';
import {getBrandDetails, listBrands} from '@modules/brand/services/brandApi.js';

export function useBrandList() {
    const brandList = ref([]);
    const paginationBrand = ref({});
    const detailsBrand = reactive({
        name: '',
        img_url: '',
        id: null
    })

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

    function getDetailsBrand(id) {
        return getBrandDetails(id)
            .then((response) => {
                detailsBrand.name = response.data.data.name;
                detailsBrand.id = response.data.data.id;
                detailsBrand.image = response.data.data.image;
                detailsBrand.img_url = '/storage/' + response.data.data.image;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    onMounted(() => loadBrandList());

    return {
        brandList,
        paginationBrand,
        detailsBrand,
        loadBrandList,
        getDetailsBrand
    };
}

