<script setup>
import { computed } from 'vue'
import { useSeoMeta, useHead } from '@unhead/vue'

const props = defineProps({
  title: { type: String, default: 'Climbing.ge' },
  description: { type: String, default: '' },
  image: { type: String, default: '' },
})

const fullImage = computed(() => {
  if (!props.image) return ''
  if (props.image.startsWith('http') || props.image.startsWith('//')) return props.image
  return props.image.startsWith('/') ? window.location.origin + props.image : window.location.origin + '/' + props.image
})

const lang = computed(() => {
  const stored = localStorage.getItem('lang')
  return stored === 'ka' ? 'ka' : 'en'
})

useHead({
  title: computed(() => props.title),
  htmlAttrs: { lang },
  link: [{ rel: 'canonical', href: computed(() => window.location.href) }],
})

useSeoMeta({
  description:        computed(() => props.description),
  ogTitle:            computed(() => props.title),
  ogDescription:      computed(() => props.description),
  ogImage:            computed(() => fullImage.value),
  ogType:             'website',
  ogUrl:              computed(() => window.location.href),
  twitterCard:        'summary_large_image',
  twitterTitle:       computed(() => props.title),
  twitterDescription: computed(() => props.description),
  twitterImage:       computed(() => fullImage.value),
})
</script>

<template></template>
