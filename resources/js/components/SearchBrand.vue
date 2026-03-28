<script setup>
import {ref} from "vue";

defineProps({
    loadBrandList: {},
    search: {
        type: String,
        default: '',
    },
})
const searchBrand = ref('');

</script>

<template>

</template>
