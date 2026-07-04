<script setup>

import {useCarModelCreate} from "../composables/useCarModelCreate.js";
import {useCarModelDetails} from "../composables/useCarModelDetails.js";
import {useCarModelEdit} from "../composables/useCarModelEdit.js";
import {useCarModelDelete} from "../composables/useCarModelDelete.js";
import CarModelCreateModal from "../components/CarModelCreateModal.vue";
import CarModelDetailsModal from "../components/CarModelDetailsModal.vue";
import CarModelEditModal from "../components/CarModelEditModal.vue";
import {useBrandList} from '../../brand/composables/useBrandList.js';
import {useModelList} from '../composables/useModelList.js';

import SearchInput from '@shared/components/SearchInput.vue';
import Pagination from '@shared/components/Pagination.vue';
import DeleteModal from '@shared/components/DeleteModal.vue';
import TableModels from '../components/TableModels.vue';
const {
    modelList,
    paginationModel,
    loadModelList,
} = useModelList();

const {
    detailsModel,
    getDetailsInfo,
    resetDetailsInfo,
} = useCarModelDetails();

const {brandList} = useBrandList();

const {
    editInfo,
    getEditInfo,
    submitUpdate,
    resetEditInfo,
    alertsEditForm,
    previewEditImage,
    handleImageEditForm,
} = useCarModelEdit({
    onSuccess: () => loadModelList(),
});

const {
    createFormPayload,
    submitCreateForm,
    isSubmittingCreateForm,
    resetCreateForm,
    alertsCreateForm,
    previewCreateImage,
    handleCreateFormImage,
} = useCarModelCreate({
    onSuccess: () => loadModelList(),
});

const {
    deleteInfo,
    getDeleteInfo,
    deleteSubmit,
    resetDeleteInfo,
} = useCarModelDelete({
    onSuccess: () => loadModelList(),
});

</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <hr>
                    <SearchInput :load-list="loadModelList" title="Busca de Modelos" label="Nome do Modelo" />
                    <hr>
                    <div class="card">
                        <div class="card-header">Modelos</div>

                        <div class="card-body">
                            <TableModels
                                :models="modelList"
                                @details="getDetailsInfo"
                                @edit="getEditInfo"
                                @delete="getDeleteInfo"
                            />
                        </div>

                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <Pagination :load-list="loadModelList" :pagination="paginationModel" />

                            <CarModelCreateModal
                                :form-payload="createFormPayload"
                                :submit-form="submitCreateForm"
                                :is-submitting="isSubmittingCreateForm"
                                :reset-form="resetCreateForm"
                                :alerts="alertsCreateForm"
                                :brands="brandList"
                                :handle-image="handleCreateFormImage"
                                :preview-image="previewCreateImage"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <CarModelDetailsModal
        :details-model="detailsModel"
        @close="resetDetailsInfo"
    />
    <CarModelEditModal
        :edit-info="editInfo"
        :brands="brandList"
        :alerts="alertsEditForm"
        :preview-image="previewEditImage"
        :handle-image="handleImageEditForm"
        @confirm="submitUpdate"
        @close="resetEditInfo"
    />
    <DeleteModal
        :delete-info="deleteInfo"
        title="Deletar Modelo"
        entity-label="o modelo"
        modal-id="deleteModel"
        @confirm="deleteSubmit"
        @close="resetDeleteInfo"
    />
</template>
