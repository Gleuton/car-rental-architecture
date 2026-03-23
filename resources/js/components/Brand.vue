<script setup>

import TableBrands from "./TableBrands.vue";
</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Busca de Marcas</div>
                    <div class="card-body">
                        <div class="row mb-0">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Nome da Marca</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input
                                        id="name"
                                        type="text"
                                        class="form-control"
                                        name="name"
                                        required
                                        autocomplete="name"
                                        autofocus>

                                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
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
                        <table-brands/>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">Adicionar Marca</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

