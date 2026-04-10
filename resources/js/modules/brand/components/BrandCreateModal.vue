<script setup>
import {onBeforeUnmount, onMounted} from 'vue';
import Modal from '@shared/components/Modal.vue';
import {useBrandCreateForm} from '@modules/brand/composables/useBrandCreateForm.js';

const emit = defineEmits(['success']);

const props = defineProps({
    modalId: {
        type: String,
        default: 'formCadBrand',
    },
    title: {
        type: String,
        default: 'Adicionar Marca',
    },
    buttonLabel: {
        type: String,
        default: 'Adicionar Marca',
    },
});

const {
    alerts,
    formPayload,
    previewImage,
    handleImage,
    resetForm,
    submitForm,
    isSubmitting,
} = useBrandCreateForm({
    modalId: props.modalId,
    onSuccess: () => emit('success'),
});

const handleModalHidden = () => {
    resetForm();
};

onMounted(() => {
    document.getElementById(props.modalId)?.addEventListener('hidden.bs.modal', handleModalHidden);
});

onBeforeUnmount(() => {
    document.getElementById(props.modalId)?.removeEventListener('hidden.bs.modal', handleModalHidden);
});

</script>

<template>
    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        :data-bs-target="`#${modalId}`"
        type="button"
    >
        {{ buttonLabel }}
    </button>

    <Modal
        :title="title"
        :modalId="modalId"
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
    </Modal>
</template>

