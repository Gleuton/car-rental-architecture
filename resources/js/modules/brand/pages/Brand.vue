<script setup>
import {useBrandList} from '@modules/brand/composables/useBrandList.js';
import useBrandDelete from '@modules/brand/composables/useBrandDelete.js';
import {useBrandForm} from '@modules/brand/composables/useBrandForm.js';
import TableBrands from '@modules/brand/components/TableBrands.vue';
import PaginationBrand from '@modules/brand/components/PaginationBrand.vue';
import BrandCreateModal from '@modules/brand/components/BrandCreateModal.vue';
import BrandDeleteModal from '@modules/brand/components/BrandDeleteModal.vue';
import Modal from '@shared/components/Modal.vue';
import SearchBrand from "@modules/brand/pages/SearchBrand.vue";

const {
    brandList,
    paginationBrand,
    detailsBrand,
    loadBrandList,
    getDetailsBrand,
} = useBrandList();

const {
    deleteInfo,
    getDeleteInfo,
    deleteSubmit,
    resetDeleteInfo,
} = useBrandDelete({
    onSuccess: () => loadBrandList(),
});

const {
    alerts,
    previewImage,
    handleImage,
    resetForm,
    updateForm,
} = useBrandForm({
    onSuccess: () => loadBrandList()
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
                        />
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <PaginationBrand
                            :load-brand-list="loadBrandList"
                            :pagination="paginationBrand"
                        />
                        <BrandCreateModal @success="loadBrandList" />
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
        <BrandDeleteModal
            :delete-info="deleteInfo"
            @confirm="deleteSubmit"
            @close="resetDeleteInfo"
        />

        <Modal
            title="Editar Marca"
            modalId="editBrand"
        >
            <template #body>
                <form>
                    <input type="hidden" name="brand_id" id="brand_id" v-model="detailsBrand.id">
                    <div class="mb-3">
                        <label for="brand_name_edt" class="form-label">Nome da Marca</label>
                        <input
                            type="text"
                            class="form-control"
                            id="brand_name_edt"
                            v-model="detailsBrand.name"
                            autofocus
                        >
                    </div>
                    <div class="mb-3">
                        <label for="brand_img_edit" class="form-label">Logo da Marca</label>
                        <input
                            type="file"
                            class="form-control"
                            id="brand_img_edit"
                            @change="handleImage"
                        >
                    </div>
                    <div class="mb-3">
                        <img :src="previewImage ?? detailsBrand.img_url" alt="logo da marca" class="img-fluid" width="200px">
                    </div>
                </form>
                <div class="alert alert-danger" role="alert" v-for="(alert, index) in alerts" :key="alert + index">
                    {{ alert }}
                </div>
            </template>
            <template #footer>
                <button
                    type="button"
                    class="btn btn-danger"
                    @click="resetForm()"
                    data-bs-dismiss="modal">Fechar
                </button>
                <button
                    type="button"
                    @click="updateForm(detailsBrand)"
                    class="btn btn-primary">Salvar
                </button>
            </template>
        </Modal>
    </div>
</template>

