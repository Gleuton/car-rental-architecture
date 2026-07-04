<script setup>
import { ref } from 'vue';

defineProps({
    loadList: {
        type: Function,
        required: true,
    },
    title: {
        type: String,
        default: 'Busca',
    },
    label: {
        type: String,
        default: 'Nome',
    },
});

const search = ref('');
</script>

<template>
    <div class="card mb-3">
        <div class="card-header">{{ title }}</div>
        <div class="card-body">
            <div class="row mb-0">
                <form class="d-flex" @submit.prevent="loadList(1, search)">
                    <label for="search" class="col-md-4 col-form-label text-md-end">{{ label }}: </label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input
                                id="search"
                                type="text"
                                class="form-control"
                                v-model="search"
                                autofocus>
                            <button
                                type="submit"
                                class="btn btn-primary d-inline-flex align-items-center gap-2">
                                <span>Buscar</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
