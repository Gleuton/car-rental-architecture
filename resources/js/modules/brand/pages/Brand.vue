<script setup>
import {useBrandList} from '../composables/useBrandList.js';
import {useBrandDetails} from '../composables/useBrandDetails.js';
import {useBrandDelete} from '../composables/useBrandDelete.js';
import {useBrandEdit} from "../composables/useBrandEdit.js";
import {useBrandCreate} from "../composables/useBrandCreate.js";

import SearchInput from '@shared/components/SearchInput.vue';
import Pagination from '@shared/components/Pagination.vue';
import DeleteModal from '@shared/components/DeleteModal.vue';
import TableBrands from '../components/TableBrands.vue';
import BrandCreateModal from '../components/BrandCreateModal.vue';
import BrandEditModal from '../components/BrandEditModal.vue';
import BrandDetailsModal from '../components/BrandDetailsModal.vue';

const {
    brandList,
    paginationBrand,
    loadBrandList,
} = useBrandList();

const {
    detailsBrand,
    getDetailsInfo,
    resetDetailsInfo,
} = useBrandDetails();

const {
    alertsCreateForm,
    createFormPayload,
    previewCreateImage,
    handleCreateFormImage,
    resetCreateForm,
    submitCreateForm,
    isSubmittingCreateForm,
} = useBrandCreate({
    onSuccess: () => loadBrandList(),
});

const {
    editInfo,
    getEditInfo,
    submitUpdate,
    resetEditInfo,
    alertsEditForm,
    previewEditImage,
    handleImageEditForm
} = useBrandEdit({
    onSuccess: () => loadBrandList(),
});

const {
    deleteInfo,
    getDeleteInfo,
    deleteSubmit,
    resetDeleteInfo,
} = useBrandDelete({
    onSuccess: () => loadBrandList(),
});

</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <SearchInput :load-list="loadBrandList" title="Busca de Marcas" label="Nome da Marca"/>
                <hr>
                <div class="card">
                    <div class="card-header">Marcas</div>

                    <div class="card-body">
                        <TableBrands
                            :brands="brandList"
                            @details="getDetailsInfo"
                            @delete="getDeleteInfo"
                            @edit="getEditInfo"
                        />
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <Pagination
                            :load-list="loadBrandList"
                            :pagination="paginationBrand"
                        />
                        <BrandCreateModal
                            :alerts="alertsCreateForm"
                            :form-payload="createFormPayload"
                            :preview-image="previewCreateImage"
                            :handle-image="handleCreateFormImage"
                            :reset-form="resetCreateForm"
                            :submit-form="submitCreateForm"
                            :is-submitting="isSubmittingCreateForm"
                        />
                    </div>
                </div>
            </div>
        </div>

        <BrandDetailsModal
            :details-brand="detailsBrand"
            @close="resetDetailsInfo"
        />
        <BrandEditModal
            :handle-image="handleImageEditForm"
            :details-brand="editInfo"
            :alerts="alertsEditForm"
            :preview-image="previewEditImage"
            @confirm="submitUpdate"
            @close="resetEditInfo"
        />
        <DeleteModal
            :delete-info="deleteInfo"
            title="Deletar Marca"
            entity-label="a marca"
            modal-id="deleteBrand"
            @confirm="deleteSubmit"
            @close="resetDeleteInfo"
        />
    </div>
</template>

