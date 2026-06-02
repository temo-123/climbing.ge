<template>
  <div class="text-center py-5">
    <span class="spinner-border" role="status"></span>
  </div>
</template>

<script>
export default {
  name: 'MakeSummitAscentPage',
  mounted() {
    const id = this.$route.params.id
    axios.get('summit/list')
      .then(r => {
        const summit = r.data.find(s => String(s.id) === String(id))
        if (summit) {
          this.$router.replace(`/summit/${summit.url_title}?make_ascent`)
        } else {
          this.$router.replace('/summits/list')
        }
      })
      .catch(() => {
        this.$router.replace('/summits/list')
      })
  },
}
</script>
