<script setup>
const emit = defineEmits(['confirm', 'close']);

defineProps({
    editInfo: {
        type: Object,
        default: null,
    },
    alerts: {
        type: Array,
        default: () => [],
    },
    previewImage: {
        type: String,
        default: null,
    },
    handleImage: {
        type: Function,
        required: true,
    },
    brands: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <modal-component
        title="Editar Modelo"
        modalId="editModel"
    >
        <template #body>
            <form @submit.prevent="">
                <div class="mb-3">
                    <label for="edit_brand_select" class="form-label">Marca</label>
                    <select
                        id="edit_brand_select"
                        v-model="editInfo.brandUuid"
                        class="form-select"
                    >
                        <option value="" disabled>Selecione uma marca</option>
                        <option
                            v-for="brand in brands"
                            :key="brand.uuid"
                            :value="brand.uuid"
                        >
                            {{ brand.name }}
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="model_name_edt" class="form-label">Nome do Modelo</label>
                    <input
                        type="text"
                        class="form-control"
                        id="model_name_edt"
                        v-model="editInfo.name"
                        autofocus
                    >
                </div>
                <div class="mb-3">
                    <label for="model_img_edt" class="form-label">Foto do Modelo</label>
                    <input
                        type="file"
                        class="form-control"
                        id="model_img_edt"
                        @change="handleImage"
                    >
                    <div v-if="previewImage ?? editInfo.img_url" class="mt-2">
                        <img
                            :src="previewImage ?? editInfo.img_url"
                            alt="preview do modelo"
                            class="img-thumbnail"
                            style="max-height: 150px;"
                        >
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label for="edit_doors_number" class="form-label">Número de portas</label>
                        <input
                            id="edit_doors_number"
                            v-model="editInfo.doorsNumber"
                            type="number"
                            class="form-control"
                            min="2"
                            max="5"
                        >
                    </div>
                    <div class="col-6">
                        <label for="edit_seats_number" class="form-label">Número de assentos</label>
                        <input
                            id="edit_seats_number"
                            v-model="editInfo.seatsNumber"
                            type="number"
                            class="form-control"
                            min="2"
                            max="7"
                        >
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    <div class="col-6">
                        <div class="form-check">
                            <input
                                id="edit_airbags"
                                v-model="editInfo.airbags"
                                type="checkbox"
                                class="form-check-input"
                            >
                            <label for="edit_airbags" class="form-check-label">Possui Airbags</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check">
                            <input
                                id="edit_abs"
                                v-model="editInfo.abs"
                                type="checkbox"
                                class="form-check-input"
                            >
                            <label for="edit_abs" class="form-check-label">Possui ABS</label>
                        </div>
                    </div>
                </div>
            </form>
            <div
                v-for="(alert, index) in alerts"
                :key="alert + index"
                class="alert alert-danger mt-3"
                role="alert"
            >
                {{ alert }}
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="btn btn-danger"
                @click="emit('close')"
                data-bs-dismiss="modal"
            >
                Fechar
            </button>
            <button
                type="button"
                class="btn btn-primary"
                @click="emit('confirm', editInfo)"
            >
                Salvar
            </button>
        </template>
    </modal-component>
</template>
