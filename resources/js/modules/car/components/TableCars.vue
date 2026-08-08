<script setup>

const emit = defineEmits(['details', 'edit', 'delete']);

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
            <th>Marca</th>
            <th>Modelo</th>
            <th>Cor</th>
            <th>Placa</th>
            <th>KM</th>
            <th>Disponível</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody v-for="car in cars" :key="car.uuid">
        <tr>
            <td>{{ car.brandName }}</td>
            <td>{{ car.carModelName }}</td>
            <td>{{ car.color }}</td>
            <td>{{ car.licensePlate }}</td>
            <td>{{ car.km }}</td>
            <td>{{ car.isAvailable ? 'Sim' : 'Não' }}</td>
            <td class="text-center">
                <div class="btn-group" role="group" aria-label="Grupo de botões">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-toggle="modal"
                        @click="emit('details', car.uuid)"
                        data-bs-target="#detailsCar">
                        Detalhes
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="emit('edit', car.uuid)"
                        data-bs-toggle="modal"
                        data-bs-target="#editCar"
                    >Editar</button>
                </div>
            </td>
        </tr>
        </tbody>

    </table>
</template>
