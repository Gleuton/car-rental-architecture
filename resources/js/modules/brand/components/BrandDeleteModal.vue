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
        title="Deletar Marca"
        modalId="deleteBrand"
    >
        <template #body>
            <div v-if="deleteInfo?.id">
                <p>Tem certeza que deseja deletar a marca <b>{{ deleteInfo.name }}</b>?</p>
                <input type="hidden" name="brand_id" id="brand_id" :value="deleteInfo.id">
            </div>
        </template>
        <template #footer>
            <div>
                <button
                    type="button"
                    class="btn btn-secondary"
                    @click="emit('close')"
                    data-bs-dismiss="modal">
                    <span>Não</span>
                </button>
            </div>
            <div v-if="deleteInfo?.id">
                <button
                    type="button"
                    @click="emit('confirm', deleteInfo.id)"
                    class="btn btn-danger">
                    <span>Sim</span>
                </button>
            </div>
        </template>
    </modal-component>
</template>

