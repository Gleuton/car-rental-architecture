<script setup>

defineProps({
    brands: {
        type: Array,
        required: true,
        default: () => []
    }
})
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
        <tbody v-for="brand in brands" :key="brand.id">
        <tr>
            <td>{{ brand.name }}</td>
            <td class="text-center">
                <img :src="'/storage/'+brand.image" alt="Imagem da marca" class="img-fluid" width="40px">
            </td>
            <td class="text-center">
                <div class="btn-group" role="group" aria-label="Grupo de botões">
                    <button type="button" class="btn btn-secondary">Detalhes</button>
                    <button type="button" class="btn btn-primary">Editar</button>
                    <button type="button" class="btn btn-danger">Excluir</button>
                </div>
            </td>
        </tr>
        </tbody>

    </table>
</template>
