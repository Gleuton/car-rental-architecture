<script setup>
import {computed, ref} from "vue";

const props = defineProps({
    loadBrandList: {},
    pagination: {
        type: Object,
        required: true,
        default: () => ({
            last_page: 1,
            current_page: 1,
            total: 0,
        })
    },
})

const currentPage = computed(() => props.pagination?.current_page ?? 1);
const lastPage = computed(() => props.pagination?.last_page ?? 1);

function previous(){
    if (currentPage > 1) {
        return currentPage - 1;
    }
    return 1;
}

function next(){
    if (currentPage < lastPage) {
        return currentPage + 1;
    }
    return lastPage;
}

</script>

<template>
    <nav class="mt-4">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#" @click.prevent="loadBrandList(previous())">Previous</a></li>
            <li class="page-item" v-for="page in pagination.last_page" :key="page">
                <a class="page-link" href="#" @click.prevent="loadBrandList(page)">{{ page }}</a>
            </li>
            <li class="page-item"><a class="page-link" href="#" @click.prevent="loadBrandList(next())">Next</a></li>
        </ul>
    </nav>
</template>

<style scoped>

</style>
