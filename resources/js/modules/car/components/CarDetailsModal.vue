<script setup>
const emit = defineEmits(['close']);

defineProps({
    detailsCar: {
        type: Object,
        default: null,
    },
});
</script>

<template>
    <modal-component
        title="Detalhes do Carro"
        modalId="detailsCar"
    >
        <template #body>
            <div v-if="detailsCar?.uuid">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="car_brand_dtl" class="form-label">Marca</label>
                        <input
                            type="text"
                            class="form-control"
                            id="car_brand_dtl"
                            :value="detailsCar.brandName"
                            disabled
                        >
                    </div>
                    <div class="col-md-6">
                        <label for="car_model_dtl" class="form-label">Modelo</label>
                        <input
                            type="text"
                            class="form-control"
                            id="car_model_dtl"
                            :value="detailsCar.carModelName"
                            disabled
                        >
                    </div>
                    <div class="col-md-4">
                        <label for="car_plate_dtl" class="form-label">Placa</label>
                        <input
                            type="text"
                            class="form-control"
                            id="car_plate_dtl"
                            :value="detailsCar.licensePlate"
                            disabled
                        >
                    </div>
                    <div class="col-md-4">
                        <label for="car_color_dtl" class="form-label">Cor</label>
                        <input
                            type="text"
                            class="form-control"
                            id="car_color_dtl"
                            :value="detailsCar.color"
                            disabled
                        >
                    </div>
                    <div class="col-md-4">
                        <label for="car_km_dtl" class="form-label">KM</label>
                        <input
                            type="text"
                            class="form-control"
                            id="car_km_dtl"
                            :value="detailsCar.km"
                            disabled
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-block">Disponível</label>
                        <input
                            type="text"
                            class="form-control"
                            :value="detailsCar.isAvailable ? 'Sim' : 'Não'"
                            disabled
                        >
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="btn btn-danger"
                @click="emit('close')"
                data-bs-dismiss="modal"
            >
                <span>Fechar</span>
            </button>
        </template>
    </modal-component>
</template>
