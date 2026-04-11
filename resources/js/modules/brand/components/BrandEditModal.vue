<script setup>

import Modal from "@shared/components/Modal.vue";

const emit = defineEmits(['confirm', 'close']);
defineProps({
    detailsBrand: {
        type: Object,
        default: null,
    },
    alerts: {
        type: Array,
        default: () => [],
    },
    previewImage: {
        type: String,
    },
    handleImage: {
        type: Function,
        required: true,
    },
});
</script>

<template>
    <Modal
        title="Editar Marca"
        modalId="editBrand"
    >
        <template #body>
            <form>
                <input type="hidden" name="brand_id" id="brand_id" v-model="detailsBrand.id">
                <div class="mb-3">
                    <label for="brand_name_edt" class="form-label">Nome da Marca</label>
                    <input
                        type="text"
                        class="form-control"
                        id="brand_name_edt"
                        v-model="detailsBrand.name"
                        autofocus
                    >
                </div>
                <div class="mb-3">
                    <label for="brand_img_edit" class="form-label">Logo da Marca</label>
                    <input
                        type="file"
                        class="form-control"
                        id="brand_img_edit"
                        @change="handleImage"
                    >
                </div>
                <div class="mb-3">
                    <img :src="previewImage ?? detailsBrand.img_url" alt="logo da marca" class="img-fluid" width="200px">
                </div>
            </form>
            <div class="alert alert-danger" role="alert" v-for="(alert, index) in alerts" :key="alert + index">
                {{ alert }}
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="btn btn-danger"
                @click="emit('close')"
                data-bs-dismiss="modal">Fechar
            </button>
            <button
                type="button"
                @click="emit('confirm', detailsBrand)"
                class="btn btn-primary">Salvar
            </button>
        </template>
    </Modal>

</template>

<style scoped>

</style>
