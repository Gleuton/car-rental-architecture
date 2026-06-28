<script setup>
import TableCars from "@modules/car/components/TableCars.vue";
import {useCarList} from '../composables/useCarList.js';

const {
    carList,
} = useCarList();


</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <hr>
                <div class="card">
                    <div class="card-header">Carros</div>

                    <div class="card-body">
                        <TableCars
                            :cars="carList"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
