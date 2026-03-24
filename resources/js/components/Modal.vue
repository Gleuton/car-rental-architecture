<script setup>

const props = defineProps({
    title: {
        type: String,
        default: 'Modal title'
    },
    modal_id: String,
})

</script>

<template>
    <div ref="modalRef" class="modal fade" :id="props.modal_id" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">{{ props.title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
