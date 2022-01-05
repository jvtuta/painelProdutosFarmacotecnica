<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-6">
        <div class="row justify-content-center">
          <form :action="action" method="get">
            <!-- Checkboxes -->
            <div class="col-12">
              <check-box-filial-component
                name="options"
                text="Matriz"
                label="matriz"
                id="matriz"
                @change="selectedOption"
              />
              <check-box-filial-component
                name="options"
                text="102 Sul"
                label="102sul"
                id="102sul"
                @change="selectedOption"
              />
              <check-box-filial-component
                name="options"
                text="302 Sul"
                label="302sul"
                id="302sul"
                @change="selectedOption"
              />
              <check-box-filial-component
                name="options"
                text="Taguatinga Centro"
                label="tcentro"
                id="tcentro"
                @change="selectedOption"
              />
              <check-box-filial-component
                name="options"
                text="Taguatinga Norte"
                label="tnorte"
                id="tnorte"
                @change="selectedOption"
              />
              <check-box-filial-component
                name="options"
                text="316 Norte"
                label="316norte"
                id="316norte"
                @change="selectedOption"
              />
              <check-box-filial-component
                name="options"
                text="Centro Clínico Sul"
                label="ccsul"
                id="ccsul"
                @change="selectedOption"
              />
              <check-box-filial-component
                name="options"
                text="Todos"
                label="todos"
                id="todos"
                :checked="true"
                @change="selectedOption"
              />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import checkBoxFilialVue from "../components/checkBoxRadioFilial.vue";

export default {
  props: {
    action: String,
    csrf: String,
    produtos: Array | Object,
  },
  data() {
    return {
      option: "todos",
    };
  },
  components: {
    "check-box-filial-component": checkBoxFilialVue,
  },
  methods: {
    selectedOption(val) {
      this.option = val;
    },
  },
};
</script>

<style>
</style>