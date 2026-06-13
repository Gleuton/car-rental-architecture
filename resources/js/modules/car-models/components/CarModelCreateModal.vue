<script setup>

const props = defineProps({
    formPayload:{
        type: Object,
        required: true,
    },
    resetForm:{
        type: Function,
        required: true,
    },
    submitForm:{
        type: Function,
        required: true,
    },
    isSubmitting:{
        type: Boolean,
        default: false,
    },
    alerts: {
        type: Array,
        default: () => [],
    },
    brands: {
        type: Array,
        default: () => [],
    },
    handleImage: {
        type: Function,
        required: false,
    },
    previewImage: {
        type: String,
        required: false,
    },
});

</script>

<template>
    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#formCadModel"
        type="button"
    >
        Cadastrar Modelo
    </button>

    <modal-component
        title="Cadastrar Modelo"
        modalId="formCadModel"
    >
        <template #body>
            <form @submit.prevent="">
                <div class="mb-3">
                    <label for="brand_select" class="form-label">Marca</label>
                    <select
                        id="brand_select"
                        v-model="formPayload.brand_uuid"
                        class="form-select"
                        required
                    >
                        <option value="" disabled selected>Selecione uma marca</option>
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
                    <label for="model_name" class="form-label">Nome da Modelo</label>
                    <input
                        id="model_name"
                        v-model="formPayload.name"
                        type="text"
                        class="form-control"
                        required
                        autofocus
                    >
                </div>
                <div class="mb-3">
                    <label for="model_img" class="form-label">Foto do Modelo</label>
                    <input
                        id="model_img"
                        type="file"
                        class="form-control"
                        required
                        @change="handleImage ? handleImage($event) : $event"
                    >
                    <div v-if="previewImage" class="mt-2">
                        <img :src="previewImage" alt="preview" class="img-thumbnail" style="max-height: 150px;" />
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <label for="doors_number" class="form-label">Número de portas</label>
                        <input
                            id="doors_number"
                            v-model="formPayload.doors_number"
                            type="number"
                            class="form-control"
                            min="2"
                            max="4"
                            required
                        >
                    </div>
                    <div class="col-6">
                        <label for="seats" class="form-label">Número de assentos</label>
                        <input
                            id="seats"
                            v-model="formPayload.seats_number"
                            type="number"
                            class="form-control"
                            min="2"
                            max="10"
                            required
                        >
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="form-check mt-4">
                            <input
                                id="airbags"
                                v-model="formPayload.airbags"
                                type="checkbox"
                                class="form-check-input"
                            >
                            <label for="airbags" class="form-check-label">Possui Airbags</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check mt-4">
                            <input
                                id="abs"
                                v-model="formPayload.abs"
                                type="checkbox"
                                class="form-check-input"
                            >
                            <label for="abs" class="form-check-label">Possui ABS</label>
                        </div>
                    </div>
                </div>
            </form>
            <div
                v-for="(alert, index) in alerts"
                :key="alert + index"
                class="alert alert-danger"
                role="alert"
            >
                {{ alert }}
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="btn btn-danger"
                @click="resetForm()"
                data-bs-dismiss="modal"
            >
                Fechar
            </button>
            <button
                type="button"
                class="btn btn-primary"
                :disabled="isSubmitting"
                @click="submitForm()"
            >
                Salvar
            </button>
        </template>
    </modal-component>
</template>

