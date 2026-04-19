<script setup>
import { onMounted, watch, nextTick } from 'vue'

const props = defineProps({
  title: {
    type: String,
    default: 'My Page Title'
  },
  description: {
    type: String,
    default: 'Page description'
  },
  image: {
    type: String,
    default: ''
  }
})

const updateHead = () => {
  nextTick(() => {
    document.title = props.title || ''

    const metas = {
      'description': { name: 'description', content: props.description },
      'og:title': { property: 'og:title', content: props.title },
      'og:description': { property: 'og:description', content: props.description },
      'og:image': { property: 'og:image', content: props.image },
      'twitter:title': { name: 'twitter:title', content: props.title },
      'twitter:description': { name: 'twitter:description', content: props.description },
      'twitter:image': { name: 'twitter:image', content: props.image }
    }

    Object.entries(metas).forEach(([key, meta]) => {
      let el = document.querySelector(`meta[${meta.name ? 'name' : 'property'}="${key}"]`)
      if (!el) {
        el = document.createElement('meta')
        if (meta.name) el.setAttribute('name', key)
        else el.setAttribute('property', key)
        document.head.appendChild(el)
      }
      el.content = meta.content || ''
    })
  })
}

watch(() => props, updateHead, { deep: true, immediate: true })

onMounted(updateHead)
</script>

<template></template>