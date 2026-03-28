<template>
  <main role="main" class="container">
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border" role="status"></div>
      <p>Make ascent</p>
    </div>
  </main>
</template>

<script>
export default {
  name: 'EditSummitPage',
  data() {
    return {
      summit: null,
    }
  },
  methods: {
    //
  }
}
</script>

<style scoped>

</style>

