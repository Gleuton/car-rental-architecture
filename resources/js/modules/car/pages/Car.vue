<script setup>
import {ref, watch} from "vue";
import TableCars from "@modules/car/components/TableCars.vue";
import {useCarList} from '@modules/car/composables/useCarList.js';
import {useCarCreate} from "@modules/car/composables/useCarCreate.js";
import {useCarDetails} from "@modules/car/composables/useCarDetails.js";
import CarCreateModal from "@modules/car/components/CarCreateModal.vue";
import CarDetailsModal from "@modules/car/components/CarDetailsModal.vue";
import {useBrandList} from "@modules/brand/composables/useBrandList.js";
import {useModelList} from "@modules/car-models/composables/useModelList.js";

const {
    carList,
    loadCarList,
} = useCarList();

const {brandList} = useBrandList();
const {modelList, loadModelList} = useModelList();

const {
    detailsCar,
    getDetailsInfo,
    resetDetailsInfo,
} = useCarDetails();

const selectedBrandUuid = ref('');

watch(selectedBrandUuid, (brandUuid) => {
    loadModelList(1, '', brandUuid);
});

const {
    createFormPayload,
    submitCreateForm,
    isSubmittingCreateForm,
    resetCreateForm,
    alertsCreateForm,
} = useCarCreate({
    onSuccess: () => loadCarList(),
});


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
                            @details="getDetailsInfo"
                        />
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <CarCreateModal
                            v-model:brand-uuid="selectedBrandUuid"
                            :form-payload="createFormPayload"
                            :submit-form="submitCreateForm"
                            :is-submitting="isSubmittingCreateForm"
                            :reset-form="resetCreateForm"
                            :alerts="alertsCreateForm"
                            :brands="brandList"
                            :models="modelList"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <CarDetailsModal
        :details-car="detailsCar"
        @close="resetDetailsInfo"
    />
</template>

<style scoped>

</style>
