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
});
</script>

<template>
    <modal-component
        title="Editar Carro"
        modalId="editCar"
    >
        <template #body>
            <form @submit.prevent="">
                <div class="row g-3">
                    <div class="col-6">
                        <label for="edit_car_brand" class="form-label">Marca</label>
                        <input
                            type="text"
                            class="form-control"
                            id="edit_car_brand"
                            :value="editInfo.brandName"
                            disabled
                        >
                    </div>
                    <div class="col-6">
                        <label for="edit_car_model" class="form-label">Modelo</label>
                        <input
                            type="text"
                            class="form-control"
                            id="edit_car_model"
                            :value="editInfo.carModelName"
                            disabled
                        >
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <label for="edit_license_plate" class="form-label">Placa</label>
                    <input
                        type="text"
                        class="form-control"
                        id="edit_license_plate"
                        v-model="editInfo.licensePlate"
                        autofocus
                    >
                </div>
                <div class="mb-3">
                    <label for="edit_color" class="form-label">Cor</label>
                    <input
                        type="text"
                        class="form-control"
                        id="edit_color"
                        v-model="editInfo.color"
                    >
                </div>
                <div class="form-check">
                    <input
                        id="edit_is_available"
                        v-model="editInfo.isAvailable"
                        type="checkbox"
                        class="form-check-input"
                    >
                    <label for="edit_is_available" class="form-check-label">Disponível</label>
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
