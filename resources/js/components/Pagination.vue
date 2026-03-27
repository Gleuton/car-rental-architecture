<script setup>
defineProps({
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

function previous(currentPage = 1){
    if (currentPage > 1) {
        return currentPage - 1;
    }
    return 1;
}

function next(currentPage = 1, lastPage = 1){
    if (currentPage < lastPage) {
        return currentPage + 1;
    }
    return lastPage;
}

</script>

<template>
    <nav class="mt-4">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#" @click.prevent="loadBrandList(previous(pagination.current_page))">Previous</a></li>
            <li class="page-item" v-for="page in pagination.last_page" :key="page">
                <a class="page-link" href="#" @click.prevent="loadBrandList(page)">{{ page }}</a>
            </li>
            <li class="page-item"><a class="page-link" href="#" @click.prevent="loadBrandList(next(pagination.current_page, pagination.last_page))">Next</a></li>
        </ul>
    </nav>
</template>

<style scoped>

</style>
