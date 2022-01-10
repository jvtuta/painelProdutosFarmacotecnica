<template>
  <div class="form-check form-check-inline p-0">
    <input class="form-check-input mx-2" :value="label" type="radio" :name="name" :id="id" v-model="radioButtonVal" />
    <label class="form-check-label" :for="id"> {{ text }} </label>
  </div>
</template>

<script>
export default {
    props: {
        text: {
            required: true,
            type: String
        },
        label: {
            required: true,
            type: String
        },
        id: {
            required: true,
            type: String
        },
        checked: Boolean,
        name,
    },
    computed: {
        radioButtonVal: {
            
            get() {
                return this.value
            }, 
            set(val) {
                this.$emit('change', val)
            }
            
        }
    }
};
</script>

<style>
</style>