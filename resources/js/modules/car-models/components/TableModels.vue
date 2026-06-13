<script setup>


const emit = defineEmits(['details', 'edit', 'delete']);

const props = defineProps({
    models: {
        type: Array,
        required: true,
        default: () => []
    },
    brands: {
        type: Array,
        required: false,
        default: () => []
    }
});

function brandNameFor(uuid) {
    const brand = (props.brands || []).find(b => b.uuid === uuid);
    return brand ? brand.name : '';
}

</script>

<template>
    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
        <tr>
            <th>Nome</th>
            <th>Marca</th>
            <th>Portas</th>
            <th>Assentos</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody v-for="model in models" :key="model.uuid">
        <tr>
            <td>{{ model.name }}</td>
            <td class="text-center">{{ brandNameFor(model.brandUuid) }}</td>
            <td class="text-center">{{ model.doorsNumber }}</td>
            <td class="text-center">{{ model.seatsNumber }}</td>
            <td class="text-center">
                <div class="btn-group" role="group" aria-label="Grupo de botões">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-toggle="modal"
                        @click="emit('details', model.uuid)"
                        data-bs-target="#detailsModel">
                        Detalhes
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="emit('edit', model.uuid)"
                        data-bs-toggle="modal"
                        data-bs-target="#editModel"
                    >Editar</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        @click="emit('delete', model.uuid)"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModel"
                    >Excluir</button>
                </div>
            </td>
        </tr>
        </tbody>

    </table>
</template>


