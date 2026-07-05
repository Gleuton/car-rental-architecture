<script setup>

import {ref} from "vue";

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
    models: {
        type: Array,
        default: () => [],
    }
});

const brand_uuid = ref('');

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
        title="Cadastrar Carros"
        modalId="formCadModel"
    >
        <template #body>
            <form @submit.prevent="">
                <div class="mb-3">
                    <label for="brand_select" class="form-label">Carro</label>
                    <select
                        id="brand_select"
                        v-model="brand_uuid"
                        class="form-select"
                        required
                    >
                        <option value="" disabled selected>Selecione a marca</option>
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
                    <label for="model_select" class="form-label">Modelo</label>
                    <select
                        id="model_select"
                        v-model="formPayload.car_model_uuid"
                        class="form-select"
                        required
                    >
                        <option value="" disabled selected>Selecione o modelo</option>
                        <option
                            v-for="model in models"
                            :key="model.uuid"
                            :value="model.uuid"
                        >
                            {{ model.name }}
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="license_plate" class="form-label">Placa do carro</label>
                    <input
                        id="license_plate"
                        v-model="formPayload.license_plate"
                        type="text"
                        class="form-control"
                        required
                        autofocus
                    >
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <label for="km" class="form-label">km rodados</label>
                        <input
                            id="km"
                            v-model="formPayload.km"
                            type="number"
                            class="form-control"
                            min="0"
                            required
                        >
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

