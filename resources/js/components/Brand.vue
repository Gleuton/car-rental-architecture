<script setup>
import {onMounted, reactive, ref} from 'vue';
import {mapBrandApiError} from '../errors/brandApiErrorMapper.js';

const formPayload = reactive({
    name: '',
    image: null,
});

const alerts = ref([]);
const success = ref(false);

const fileInput = ref(null)
fileInput.value = null;

function cleanAlerts() {
    alerts.value = [];
    success.value = false;
}

function resetForm() {
    formPayload.name = '';
    formPayload.image = null;
    cleanAlerts();

    if (fileInput.value) {
        fileInput.value.value = '';
    }
}

function submitForm() {
    cleanAlerts();

    const token = localStorage.getItem('token');
    const config = {headers: {'Authorization': `Bearer ${token}`}};
    const formData = new FormData();

    formData.append('name', formPayload.name);
    if (formPayload.image) {
        formData.append('image', formPayload.image);
    }

    axios.post('/api/brands', formData, config)
        .then(() => {
            resetForm();
            success.value = true;
        })
        .catch((error) => {
            alerts.value = mapBrandApiError(error);
        });
}

const brandList = ref([]);
const paginationBrand = ref({});

function loadBrandList(page = 1) {
    const token = localStorage.getItem('token');
    const url = '/api/brands';
    const params = '?page=' + page + '&per_page=2';
    const config = {headers: {'Authorization': `Bearer ${token}`}};

    axios.get(url + params, config)
        .then((response) => {
            brandList.value = response.data.data;
            paginationBrand.value = response.data.meta;
        })
        .catch((error) => {
            console.error(error);
        });
}

onMounted(() => loadBrandList());

</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Busca de Marcas</div>
                    <div class="card-body">
                        <div class="row mb-0">
                            <label for="search" class="col-md-4 col-form-label text-md-end">Nome da Marca</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input
                                        id="search"
                                        type="text"
                                        class="form-control"
                                        name="search"
                                        autocomplete="name"
                                        autofocus>

                                    <button type="submit"
                                            class="btn btn-primary d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>Buscar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="card">
                    <div class="card-header">Marcas</div>

                    <div class="card-body">
                        <table-brands-component :brands="brandList"/>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <pagination-component
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
                            @change="formPayload.image = $event.target.files[0]"
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
    </div>
</template>

