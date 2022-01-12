<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-sm-10">
        <div class="card p-3">
          <form :action="action" method="get">
            <div class="row">
              <div class="d-flex justify-content-center border-bottom mb-4">
                <span class="text-center fs-4">Filiais</span>
              </div>
              <!-- Checkboxes -->
              <div class="col-10 p-2">
                <check-box-filial-component
                  name="options"
                  text="Matriz"
                  label="01"
                  id="matriz"
                  @change="selectedOption"
                />
                <check-box-filial-component
                  name="options"
                  text="102 Sul"
                  label="02"
                  id="102sul"
                  @change="selectedOption"
                />
                <check-box-filial-component
                  name="options"
                  text="302 Sul"
                  label="06"
                  id="302sul"
                  @change="selectedOption"
                />
                <check-box-filial-component
                  name="options"
                  text="Taguatinga Centro"
                  label="03"
                  id="tcentro"
                  @change="selectedOption"
                />
                <check-box-filial-component
                  name="options"
                  text="Taguatinga Norte"
                  label="04"
                  id="tnorte"
                  @change="selectedOption"
                />
                <check-box-filial-component
                  name="options"
                  text="316 Norte"
                  label="12"
                  id="316norte"
                  @change="selectedOption"
                />
                <check-box-filial-component
                  name="options"
                  text="Centro Clínico Sul"
                  label="08"
                  id="ccsul"
                  @change="selectedOption"
                />
                <check-box-filial-component
                  name="options"
                  text="Teleatendimento"
                  label="15"
                  id="teleatendimento"
                  @change="selectedOption"
                />
                <check-box-filial-component
                  name="options"
                  text="Almoxarifado"
                  label="16"
                  id="almoxarifado"
                  @change="selectedOption"
                />
                <check-box-filial-component
                  name="options"
                  text="Todos"
                  label="00"
                  id="todos"
                  @change="selectedOption"
                />
              </div>
            </div>
            <div class="row mt-4 p-1">
              <div class="col">
                <label for="periodo" class="form-label fs-5">Data:</label>
                <input
                  type="month"
                  name="periodo"
                  id="periodo"
                  v-model="date_val"
                  class="form-control"
                />
              </div>
            </div>
            <div class="row mt-4 p-1">
              <div class="d-grid gap-2">
                <button
                  type="submit"
                  class="btn btn-primary"
                  :disabled="date_val === true"
                  @click="loadExcel()"
                >
                  <span
                    class="spinner-grow spinner-grow-sm"
                    role="status"
                    aria-hidden="true"
                    v-if="loading"
                  ></span>
                  <span v-if="!loading">Carregar</span>
                  <span v-else>Carregando...</span>
                </button>
                
              </div>
              <span v-if="loading">Tempo estimado para download: 5mins...</span>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="row justify-content-center"></div>
  </div>
</template>

<script>
import checkBoxFilialVue from "../components/check-box-radio-filial-component.vue";

export default {
  props: {
    action: String,
    csrf: String,
    produtos: Array | Object,
  },
  data() {
    return {
      option: "00",
      date_val: true,
      loading: false,
    };
  },
  watch: {
    date_val(val) {
      console.log(val);
    },
  },
  components: {
    "check-box-filial-component": checkBoxFilialVue,
  },
  methods: {
    selectedOption(val) {
      this.option = val;
    },
    loadExcel() {
      this.loading = true
      console.log('startou')
      setTimeout(()=>{
        console.log('terminou')
        location.reload()
      }, 220000)
    }
  },
  mounted() {
    let date = new Date();
    const mounth =
      date.getMonth() + 1 <= 9
        ? "0" + (date.getMonth() + 1)
        : date.getMonth() + 1;
    date = date.getFullYear() + "-" + mounth;
    this.date_val = date;
  },
};
</script>

<style>
</style>