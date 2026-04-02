<script setup>
import {computed} from "vue";

const props = defineProps({
    loadBrandList: {
        type: Function,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
})

const currentPage = computed(() => props.pagination?.current_page ?? 1);
const lastPage = computed(() => props.pagination?.last_page ?? 1);

const isFirstPage = computed(() => currentPage.value <= 1);
const isLastPage = computed(() => currentPage.value >= lastPage.value);

const previousPage = computed(() => {
    if (isFirstPage.value) return 1;
    return currentPage.value - 1;
});

const nextPage = computed(() => {
    if (isLastPage.value) return lastPage.value;
    return currentPage.value + 1;
});

</script>

<template>
    <nav class="mt-4">
        <ul class="pagination">
            <li class="page-item" :class="{ disabled: isFirstPage }">
                <a class="page-link" href="#" @click.prevent="loadBrandList(previousPage)">Previous</a>
            </li>
            <li class="page-item" v-for="page in lastPage" :key="page">
                <a class="page-link" href="#"
                   :class="{ active: page === currentPage }"
                   @click.prevent="loadBrandList(page)">
                    {{ page }}
                </a>
            </li>
            <li class="page-item" :class="{ disabled: isLastPage }">
                <a class="page-link" href="#" @click.prevent="loadBrandList(nextPage)">Next</a>
            </li>
        </ul>
    </nav>
</template>

<style scoped>

</style>
