import { onMounted, ref } from 'vue';

export function useEntityList(fetchFn) {
    const items = ref([]);
    const pagination = ref({});
    const currentSearch = ref('');

    function loadList(page = 1, search = '', extraParams = {}) {
        currentSearch.value = search;
        return fetchFn({ page, search, ...extraParams })
            .then((response) => {
                items.value = response.data.data;
                pagination.value = response.data.meta;
            })
            .catch((error) => {
                console.error(error);
            });
    }

    onMounted(() => loadList());

    return { items, pagination, currentSearch, loadList };
}
