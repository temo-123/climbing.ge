<script setup>
// Shared with every public site: services/seo/pageMeta.js (keeps the server's SEO tags on
// the page the server rendered; writes its own, with the same URL rules, after navigation)
import { use_page_meta } from '../../../services/seo/pageMeta'

const props = defineProps({
  title: { type: String, default: 'Climbing.ge' },
  description: { type: String, default: '' },
  image: { type: String, default: '' },
})

use_page_meta(props)
</script>

<template></template>
