<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10 col-lg-8 col-xl-6 col-sm-12">
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
                  class="btn btn-primary"
                  :disabled="loading == true"
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
              <span v-if="loading">Tempo estimado para download entre 10s até 30s</span>
            </div>
          </form>
        </div>
      </div>
    </div>
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

      this.getData();

    },
    async getData() {
      const url = `api/v1/produtos?options=${this.option}&date_val=${this.date_val}`
      const config = {
          method:"get",
          url,
          headers: {
            responseType: 'blob',
            "Content-Type": "application/x-www-form-urlencoded",
          },
      }

    //   try {
    //     const data = await axios(config);
    //     console.log(data)
    //     fileDownload(data.data, "teste.xlsx");
    //   } catch ( err ) {
    //       console.log(err)
    //   }
    const config2 = {
            method: "get",
            url: '/'
    }
    for(let i = 0; i < 10; i++) {
        //Liberar memória no srv

        await axios(config2);
    }
    try  {
        const data = await (await axios(config)).data;
        let excel = await fetch('storage/'+data);
        excel = await excel.blob();
        let fileURL = window.URL.createObjectURL(excel);

        let fileLink = document.createElement("a");
        fileLink.href = fileURL;
        fileLink.setAttribute("download", data);
        document.body.appendChild(fileLink);
        fileLink.click();
        document.body.removeChild(fileLink);

    } catch(err) {
        console.log(err);

        return this.loadExcel();
    }

    for(let i = 0; i < 10; i++) {
        //Liberar memória no srv

        axios(config2);
    }

    this.loading = false;

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
