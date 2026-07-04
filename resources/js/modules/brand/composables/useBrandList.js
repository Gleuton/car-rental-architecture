import { useEntityList } from '@shared/composables/useEntityList.js';
import { listBrands } from '@modules/brand/services/brandApi.js';

export function useBrandList() {
    const { items: brandList, pagination: paginationBrand, loadList: loadBrandList } = useEntityList(listBrands);
    return { brandList, paginationBrand, loadBrandList };
}
