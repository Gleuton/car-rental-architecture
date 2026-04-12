<script setup>
defineProps({
    detailsBrand: {
        type: Object,
        default: null,
    },
});
</script>

<template>
    <modal-component
        title="Detalhes da Marca"
        modalId="detailsBrand"
    >
        <template #body>
            <div v-if="detailsBrand">
                <div class="row">
                    <div class="col-md-6">
                        <label for="brand_name_dtl" class="form-label">Nome da Marca</label>
                        <input
                            type="text"
                            class="form-control"
                            id="brand_name_dtl"
                            :value="detailsBrand.name"
                            disabled
                        >
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Logo da Marca</label>
                        <div class="d-flex justify-content-center">
                            <img :src="detailsBrand.img_url" alt="logo da marca" class="img-fluid" width="300px">
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="btn btn-danger"
                data-bs-dismiss="modal"
            >
                <span>Fechar</span>
            </button>
        </template>
    </modal-component>
</template>

