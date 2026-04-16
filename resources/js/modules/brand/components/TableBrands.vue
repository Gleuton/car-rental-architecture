<script setup>

const emit = defineEmits(['details', 'edit', 'delete']);

defineProps({
    brands: {
        type: Array,
        required: true,
        default: () => []
    }
});

</script>

<template>
    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
        <tr>
            <th>Nome</th>
            <th>Imagem</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody v-for="brand in brands" :key="brand.uuid">
        <tr>
            <td>{{ brand.name }}</td>
            <td class="text-center">
                <img :src="'/storage/'+brand.image" alt="Imagem da marca" class="img-fluid" width="40px">
            </td>
            <td class="text-center">
                <div class="btn-group" role="group" aria-label="Grupo de botões">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-toggle="modal"
                        @click="emit('details', brand.uuid)"
                        data-bs-target="#detailsBrand">
                        Detalhes
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="emit('edit', brand.uuid)"
                        data-bs-toggle="modal"
                        data-bs-target="#editBrand"
                    >Editar</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        @click="emit('delete', brand.uuid)"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteBrand"
                    >Excluir</button>
                </div>
            </td>
        </tr>
        </tbody>

    </table>
</template>
