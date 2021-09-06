<template>
  <vue-search 
    @itemselected="itemselected(this.results)" 
    :source-field="'name'" 
    :show-new-botton="false"
    :search-by-field="false"
    :api-source="'http://climbing.loc'"
    placeholder='Search'
    :DataDefault="results"
    :dataFiltered="results"
    ></vue-search>
</template>

<script>
import 'vue-input-search/dist/vue-search.css' //https://github.com/teclaelvis01/vue-search#readme
import VueSearch from 'vue-input-search/dist/vue-search.common' //https://www.npmjs.com/package/vue-input-search
export default {
  data() {
    return {
      results: ['eeeee', 'rrrrr'] ,
    };
  },
  components:{
      'vue-search':VueSearch
  },
  methods: {
    searchMembers() {
      
    },
  },
};
</script>
