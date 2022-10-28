<template>
  <div>
    <multiselect
      v-model="selected"
      :options="options">
    </multiselect>
  </div>
</template>

<script>
  import Multiselect from 'vue-multiselect' // https://github.com/shentao/vue-multiselect
  export default {
    components: { Multiselect },
    data () {
      return {
        selected: null,
        // options: ['list', 'of', 'options']
        options: []
      }
    },
    mounted() {
      this.get_selected_data()
    },
    methods: {
        get_selected_data() {
            axios
            .get('api/get_selected_data/')
            .then(response => {
                this.options = response.data
            })
            .catch(error =>{
            })
        },
    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>