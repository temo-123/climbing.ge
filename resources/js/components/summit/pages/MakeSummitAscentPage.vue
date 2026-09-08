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
    // A QR scan resolves through here, often on weak signal at the summit —
    // fetching just this one summit's url_title (instead of the full
    // published-summits list, as before) means one small request instead of
    // downloading and searching every summit just to redirect.
    axios.get('summit/find/' + id)
      .then(r => {
        this.$router.replace(`/summit/${r.data.url_title}?make_ascent`)
      })
      .catch(() => {
        this.$router.replace('/summits/list')
      })
  },
}
</script>
