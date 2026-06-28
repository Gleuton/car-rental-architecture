<script setup>

import {useCarModelCreate} from "../composables/useCarModelCreate.js";
import {useCarModelDetails} from "../composables/useCarModelDetails.js";
import {useCarModelEdit} from "../composables/useCarModelEdit.js";
import {useCarModelDelete} from "../composables/useCarModelDelete.js";
import CarModelCreateModal from "../components/CarModelCreateModal.vue";
import CarModelDetailsModal from "../components/CarModelDetailsModal.vue";
import CarModelEditModal from "../components/CarModelEditModal.vue";
import CarModelDeleteModal from "../components/CarModelDeleteModal.vue";
import {useBrandList} from '../../brand/composables/useBrandList.js';
import {useModelList} from '../composables/useModelList.js';

import SearchModel from '../components/SearchModel.vue';
import TableModels from '../components/TableModels.vue';
import PaginationModel from '../components/PaginationModel.vue';
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
                    <SearchModel :load-model-list="loadModelList" />
                    <hr>
                    <div class="card">
                        <div class="card-header">Modelos</div>

                        <div class="card-body">
                            <TableModels
                                :models="modelList"
                                :brands="brandList"
                                @details="getDetailsInfo"
                                @edit="getEditInfo"
                                @delete="getDeleteInfo"
                            />
                        </div>

                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <PaginationModel :load-model-list="loadModelList" :pagination="paginationModel" />

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
        :brands="brandList"
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
    <CarModelDeleteModal
        :delete-info="deleteInfo"
        @confirm="deleteSubmit"
        @close="resetDeleteInfo"
    />
</template>

