<script setup>
const emit = defineEmits(['confirm', 'close']);

defineProps({
    deleteInfo: {
        type: Object,
        default: null,
    },
});
</script>

<template>
    <modal-component
        title="Deletar Modelo"
        modalId="deleteModel"
    >
        <template #body>
            <div v-if="deleteInfo?.uuid">
                <p>Tem certeza que deseja deletar o modelo <b>{{ deleteInfo.name }}</b>?</p>
                <input type="hidden" :value="deleteInfo.uuid">
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="btn btn-secondary"
                @click="emit('close')"
                data-bs-dismiss="modal"
            >
                <span>Não</span>
            </button>
            <div v-if="deleteInfo?.uuid">
                <button
                    type="button"
                    class="btn btn-danger"
                    @click="emit('confirm', deleteInfo.uuid)"
                >
                    <span>Sim</span>
                </button>
            </div>
        </template>
    </modal-component>
</template>
