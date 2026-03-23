<script setup>
import {ref} from "vue";

const brands = ref([
    {id: 1, name: 'Marca Exemplo'},
    {id: 2, name: 'Marca Exemplo 2'},
    {id: 3, name: 'Marca Exemplo 3'},
]);
</script>

<template>
    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody v-for="brand in brands" :key="brand.id">
        <tr>
            <td>{{ brand.id }}</td>
            <td>{{ brand.name }}</td>
            <td class="text-center">
                <div class="btn-group" role="group" aria-label="Grupo de botões">
                    <button type="button" class="btn btn-primary" :value="brand.id">Editar</button>
                    <button type="button" class="btn btn-danger" :value="brand.id">Excluir</button>
                </div>
            </td>
        </tr>
        </tbody>

    </table>
</template>
