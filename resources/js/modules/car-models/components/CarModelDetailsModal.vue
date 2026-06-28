<script setup>
const emit = defineEmits(['close']);

defineProps({
    detailsModel: {
        type: Object,
        default: null,
    },
    brands: {
        type: Array,
        default: () => [],
    },
});

function brandNameFor(brands, uuid) {
    const brand = (brands || []).find(b => b.uuid === uuid);
    return brand ? brand.name : '';
}
</script>

<template>
    <modal-component
        title="Detalhes do Modelo"
        modalId="detailsModel"
    >
        <template #body>
            <div v-if="detailsModel?.uuid">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="model_name_dtl" class="form-label">Nome do Modelo</label>
                        <input
                            type="text"
                            class="form-control"
                            id="model_name_dtl"
                            :value="detailsModel.name"
                            disabled
                        >
                    </div>
                    <div class="col-md-6">
                        <label for="model_brand_dtl" class="form-label">Marca</label>
                        <input
                            type="text"
                            class="form-control"
                            id="model_brand_dtl"
                            :value="brandNameFor(brands, detailsModel.brandUuid)"
                            disabled
                        >
                    </div>
                    <div class="col-md-4">
                        <label for="model_doors_dtl" class="form-label">Portas</label>
                        <input
                            type="text"
                            class="form-control"
                            id="model_doors_dtl"
                            :value="detailsModel.doorsNumber"
                            disabled
                        >
                    </div>
                    <div class="col-md-4">
                        <label for="model_seats_dtl" class="form-label">Assentos</label>
                        <input
                            type="text"
                            class="form-control"
                            id="model_seats_dtl"
                            :value="detailsModel.seatsNumber"
                            disabled
                        >
                    </div>
                    <div class="col-md-4">
                        <label for="model_airbags_dtl" class="form-label">Airbags</label>
                        <input
                            type="text"
                            class="form-control"
                            id="model_airbags_dtl"
                            :value="detailsModel.airbags"
                            disabled
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-block">ABS</label>
                        <input
                            type="text"
                            class="form-control"
                            :value="detailsModel.abs ? 'Sim' : 'Não'"
                            disabled
                        >
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Imagem do Modelo</label>
                        <div class="d-flex justify-content-center">
                            <img :src="detailsModel.img_url" alt="imagem do modelo" class="img-fluid" width="300px">
                        </div>
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
