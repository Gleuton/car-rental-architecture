<script setup>
import {useBrandList} from '@modules/brand/composables/useBrandList.js';
import {useBrandDelete} from '@modules/brand/composables/useBrandDelete.js';
import {useBrandEdit} from "@modules/brand/composables/useBrandEdit.js";
import {useBrandCreate} from "@modules/brand/composables/useBrandCreate.js";

import TableBrands from '@modules/brand/components/TableBrands.vue';
import PaginationBrand from '@modules/brand/components/PaginationBrand.vue';
import BrandCreateModal from '@modules/brand/components/BrandCreateModal.vue';
import BrandDeleteModal from '@modules/brand/components/BrandDeleteModal.vue';
import Modal from '@shared/components/Modal.vue';
import SearchBrand from "@modules/brand/components/SearchBrand.vue";
import BrandEditModal from "@modules/brand/components/BrandEditModal.vue";


const {
    brandList,
    paginationBrand,
    detailsBrand,
    loadBrandList,
    getDetailsBrand,
} = useBrandList();

const {
    alertsCreateForm,
    createFormPayload,
    previewCreateImage,
    handleCrateFormImage,
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
                <SearchBrand :load-brand-list="loadBrandList"/>
                <hr>
                <div class="card">
                    <div class="card-header">Marcas</div>

                    <div class="card-body">
                        <TableBrands
                            :brands="brandList"
                            :details-brand="getDetailsBrand"
                            @delete="getDeleteInfo"
                            @edit="getEditInfo"
                        />
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <PaginationBrand
                            :load-brand-list="loadBrandList"
                            :pagination="paginationBrand"
                        />
                        <BrandCreateModal
                            :alerts="alertsCreateForm"
                            :form-payload="createFormPayload"
                            :preview-image="previewCreateImage"
                            :handle-image="handleCrateFormImage"
                            :reset-form="resetCreateForm"
                            :submit-form="submitCreateForm"
                            :is-submitting="isSubmittingCreateForm"
                        />
                    </div>
                </div>
            </div>
        </div>

        <Modal
            title="Detalhes da Marca"
            modalId="detailsBrand"
        >
            <template #body>
                <div v-if="detailsBrand">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="brand_name_dtl" class="form-label">Nome da Marca</label>
                            <input
                                type="text"
                                class="form-control"
                                id="brand_name_dtl"
                                :value="detailsBrand.name"
                                disabled
                            >
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Logo da Marca</label>
                            <div class="d-flex justify-content-center">
                                <img :src="detailsBrand.img_url" alt="logo da marca" class="img-fluid"
                                     width="300px">
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button
                    type="button"
                    class="btn btn-danger"
                    data-bs-dismiss="modal">
                    <span>Fechar</span>
                </button>
            </template>
        </Modal>
        <BrandEditModal
            :handle-image="handleImageEditForm"
            :details-brand="editInfo"
            :alerts="alertsEditForm"
            :preview-image="previewEditImage"
            @confirm="submitUpdate"
            @close="resetEditInfo"
        />
        <BrandDeleteModal
            :delete-info="deleteInfo"
            @confirm="deleteSubmit"
            @close="resetDeleteInfo"
        />

    </div>
</template>

