<script setup>
const emit = defineEmits(['confirm', 'close']);

defineProps({
    deleteInfo: {
        type: Object,
        default: null,
    },
    title: {
        type: String,
        default: 'Deletar',
    },
    entityLabel: {
        type: String,
        default: 'o item',
    },
    modalId: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <modal-component :title="title" :modal-id="modalId">
        <template #body>
            <div v-if="deleteInfo?.uuid">
                <p>Tem certeza que deseja deletar {{ entityLabel }} <b>{{ deleteInfo.name }}</b>?</p>
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="btn btn-secondary"
                @click="emit('close')"
                data-bs-dismiss="modal">
                <span>Não</span>
            </button>
            <div v-if="deleteInfo?.uuid">
                <button
                    type="button"
                    class="btn btn-danger"
                    @click="emit('confirm', deleteInfo.uuid)">
                    <span>Sim</span>
                </button>
            </div>
        </template>
    </modal-component>
</template>
