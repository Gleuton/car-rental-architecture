<script setup>

const props = defineProps({
    cars: {
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
            <th>Placa</th>
            <th>Cor</th>
            <th>Disponível</th>
            <th>KM</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody v-for="car in cars" :key="car.uuid">
        <tr>
            <td>{{ car.licensePlate }}</td>
            <td>{{ car.color }}</td>
            <td>{{ car.isAvailable ? 'Sim' : 'Não' }}</td>
            <td>{{ car.km }}</td>
            <td class="text-center">

            </td>
        </tr>
        </tbody>

    </table>
</template>
