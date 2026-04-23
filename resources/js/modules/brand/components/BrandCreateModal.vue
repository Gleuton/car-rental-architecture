<script setup>

const props = defineProps({
    alerts: {
        type: Array,
        default: () => [],
    },
    formPayload:{
        type: Object,
        default: () => ({
            name: '',
            img_url: '',
        }),
    },
    previewImage:{
        type: String,
    },
    handleImage:{
        type: Function,
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

});

</script>

<template>
    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#formCadBrand"
        type="button"
    >
        Cadastrar Marca
    </button>

    <modal-component
        title="Cadastrar Marca"
        modalId="formCadBrand"
    >
        <template #body>
            <form @submit.prevent="submitForm">
                <div class="mb-3">
                    <label for="brand_name" class="form-label">Nome da Marca</label>
                    <input
                        id="brand_name"
                        v-model="formPayload.name"
                        type="text"
                        class="form-control"
                        required
                        autofocus
                    >
                </div>
                <div class="mb-3">
                    <label for="brand_img" class="form-label">Logo da Marca</label>
                    <input
                        id="brand_img"
                        type="file"
                        class="form-control"
                        @change="handleImage"
                        required
                    >
                </div>
                <div v-if="previewImage" class="mb-3">
                    <img :src="previewImage" alt="logo da marca" class="img-fluid" width="200px">
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

