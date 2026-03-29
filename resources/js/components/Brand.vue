<script setup>
import {useBrandList} from '../composables/useBrandList.js';
import {useBrandForm} from "../composables/useBrandForm.js";

const {alerts, success, formPayload, fileInput, resetForm, submitForm} = useBrandForm()
const {brandList, paginationBrand, searchBrand, detailsBrandId, loadBrandList, detailsBrand} = useBrandList();

</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Busca de Marcas</div>
                    <div class="card-body">
                        <div class="row mb-0">
                            <form class="d-flex" @submit.prevent="loadBrandList()">
                                <label for="search" class="col-md-4 col-form-label text-md-end">Nome da Marca: </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input
                                            id="search"
                                            type="text"
                                            class="form-control"
                                            v-model="searchBrand"
                                            autofocus>

                                        <button
                                            type="submit"
                                            class="btn btn-primary d-inline-flex align-items-center gap-2">
                                            <span>Buscar</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card">
                    <div class="card-header">Marcas</div>

                    <div class="card-body">
                        <table-brands-component :brands="brandList" :details-brand="detailsBrand"/>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <pagination-brand-component
                            :load-brand-list="loadBrandList"
                            :pagination="paginationBrand"
                        />
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formCadBrand">
                            Adicionar Marca
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <modal-component
            title="Adicionar Marca"
            modal_id="formCadBrand"
        >
            <template #body>
                <form>
                    <div class="mb-3">
                        <label for="brand_name" class="form-label">Nome da Marca</label>
                        <input
                            type="text"
                            class="form-control"
                            id="brand_name"
                            v-model="formPayload.name"
                            required="required"
                            autofocus
                        >
                    </div>
                    <div class="mb-3">
                        <label for="brand_img" class="form-label">Logo da Marca</label>
                        <input
                            type="file"
                            class="form-control"
                            id="brand_img"
                            ref="fileInput"
                            required
                        >
                    </div>
                </form>
                <div class="alert alert-danger" role="alert" v-for="alert in alerts" :key="alert">
                    {{ alert }}
                </div>
                <div class="alert alert-success" role="alert" v-if="success">
                    Marca adicionada com sucesso!
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
                    @click="submitForm()"
                    class="btn btn-primary">Salvar
                </button>
            </template>
        </modal-component>
        <modal-component
            title="Detalhes da Marca"
            modal_id="formDetailsBrand"
        >
            <template #body>
                <div v-if="detailsBrandId">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="brand_name" class="form-label">Nome da Marca</label>
                            <input
                                type="text"
                                class="form-control"
                                id="brand_name"
                                :value="detailsBrandId.name"
                                disabled="disabled"
                            >
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Logo da Marca</label>
                            <div class="d-flex justify-content-center">
                                <img :src="'/storage/'+detailsBrandId.image" alt="logo da marca" class="img-fluid"
                                     width="300px">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger" role="alert" v-for="alert in alerts" :key="alert">
                    {{ alert }}
                </div>
                <div class="alert alert-success" role="alert" v-if="success">
                    Marca adicionada com sucesso!
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
        </modal-component>
    </div>
</template>

