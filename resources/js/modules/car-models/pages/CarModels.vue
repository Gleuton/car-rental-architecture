<script setup>

import {useCarModelCreate} from "../composables/useCarModelCreate.js";
import {useBrandList} from '@modules/brand/composables/useBrandList.js';
import CarModelCreateModal from "../components/CarModelCreateModal.vue";

const {
    createFormPayload,
    submitCreateForm,
    isSubmittingCreateForm,
    resetCreateForm,
    alertsCreateForm,
    previewCreateImage,
    handleCreateFormImage,
} = useCarModelCreate({
    onSuccess: () => console.log('CarModel created successfully!'),
});

const {brandList} = useBrandList();

</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <hr>
                    <div class="card">
                        <div class="card-header">Marcas</div>

                        <div class="card-body">

                        </div>

                        <div class="card-footer d-flex justify-content-between align-items-center">
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
</template>

