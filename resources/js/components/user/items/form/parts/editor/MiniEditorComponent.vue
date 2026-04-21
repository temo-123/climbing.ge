<template>
  <QuillEditor
    v-model:content="content"
    contentType="html"
    :options="editorOptions"
    @update:content="handleUpdate"
    style="height: 100px"
  />
</template>

<script setup>
import { ref, watch } from 'vue'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

// Props & Emits
const props = defineProps(['modelValue'])
const emit = defineEmits(['update:modelValue'])

// State
const content = ref(props.modelValue || '')

// Editor options
const editorOptions = {
  modules: {
    toolbar: [
      ['bold', 'italic'],
      [{ list: 'bullet' }],
      ['clean']
    ]
  }
}

// Watch for external changes
watch(() => props.modelValue, (newVal) => {
  content.value = newVal || ''
})

// Emit updates
const handleUpdate = (newContent) => {
  emit('update:modelValue', newContent)
}
</script>