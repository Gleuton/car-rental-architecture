<script setup>
import {ref} from "vue";

defineProps({
  loadModelList: {
      type: Function,
  },
});
const search = ref('');

</script>

<template>
  <div class="card mb-3">
    <div class="card-header">Busca de Modelos</div>
    <div class="card-body">
      <div class="row mb-0">
        <form class="d-flex" @submit.prevent="loadModelList(1, search)">
          <label for="search" class="col-md-4 col-form-label text-md-end">Nome do Modelo: </label>
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

