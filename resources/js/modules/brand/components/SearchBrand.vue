<script setup>
import {ref} from "vue";

defineProps({
  loadBrandList: {
      type: Function,
  },
});
const search = ref('');

</script>

<template>
  <div class="card">
    <div class="card-header">Busca de Marcas</div>
    <div class="card-body">
      <div class="row mb-0">
        <form class="d-flex" @submit.prevent="loadBrandList(1, search)">
          <label for="search" class="col-md-4 col-form-label text-md-end">Nome da Marca: </label>
          <div class="col-md-6">
            <div class="input-group">
              <input
                  id="search"
                  type="text"
                  class="form-control"
                  v-model="search"
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
</template>
