<script setup>
import {ref, watch} from "vue";
import TableCars from "@modules/car/components/TableCars.vue";
import {useCarList} from '@modules/car/composables/useCarList.js';
import {useCarCreate} from "@modules/car/composables/useCarCreate.js";
import {useCarDetails} from "@modules/car/composables/useCarDetails.js";
import {useCarEdit} from "@modules/car/composables/useCarEdit.js";
import {useCarDelete} from "@modules/car/composables/useCarDelete.js";
import CarCreateModal from "@modules/car/components/CarCreateModal.vue";
import CarDetailsModal from "@modules/car/components/CarDetailsModal.vue";
import CarEditModal from "@modules/car/components/CarEditModal.vue";
import DeleteModal from '@shared/components/DeleteModal.vue';
import SearchInput from '@shared/components/SearchInput.vue';
import Pagination from '@shared/components/Pagination.vue';
import {useBrandList} from "@modules/brand/composables/useBrandList.js";
import {useModelList} from "@modules/car-models/composables/useModelList.js";

const {
    carList,
    paginationCar,
    loadCarList,
} = useCarList();

const {brandList} = useBrandList();
const {modelList, loadModelList} = useModelList();

const {
    detailsCar,
    getDetailsInfo,
    resetDetailsInfo,
} = useCarDetails();

const {
    editInfo,
    getEditInfo,
    submitUpdate,
    resetEditInfo,
    alertsEditForm,
} = useCarEdit({
    onSuccess: () => loadCarList(),
});

const {
    deleteInfo,
    getDeleteInfo,
    deleteSubmit,
    resetDeleteInfo,
} = useCarDelete({
    onSuccess: () => loadCarList(),
});

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
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <hr>
                    <SearchInput :load-list="loadCarList" title="Busca de Carros" label="Placa do Carro" />
                    <hr>
                    <div class="card">
                        <div class="card-header">Carros</div>

                        <div class="card-body">
                            <TableCars
                                :cars="carList"
                                @details="getDetailsInfo"
                                @edit="getEditInfo"
                                @delete="getDeleteInfo"
                            />
                        </div>

                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <Pagination :load-list="loadCarList" :pagination="paginationCar" />

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
    </div>

    <CarDetailsModal
        :details-car="detailsCar"
        @close="resetDetailsInfo"
    />
    <CarEditModal
        :edit-info="editInfo"
        :alerts="alertsEditForm"
        @confirm="submitUpdate"
        @close="resetEditInfo"
    />
    <DeleteModal
        :delete-info="deleteInfo"
        title="Deletar Carro"
        entity-label="o carro de placa"
        modal-id="deleteCar"
        @confirm="deleteSubmit"
        @close="resetDeleteInfo"
    />
</template>

<style scoped>

</style>
