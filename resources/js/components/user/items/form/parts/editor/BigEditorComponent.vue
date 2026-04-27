<template>
  <div>
    <button 
      type="button" 
      @click="showSource = !showSource" 
      class="btn btn-secondary mb-2"
      :class="{ 'btn-success': showSource }"
    >
      {{ showSource ? 'Hide Source' : 'Check Source' }}
    </button>
    
    <div v-if="showSource" class="source-container mb-3">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">HTML Source Code</h6>
        <button 
          type="button" 
          @click="copySource" 
          class="btn btn-secondary btn-sm"
        >
          Copy to Clipboard
        </button>
      </div>
      <div style="max-height: 400px; overflow-y: auto;">
        <textarea 
          v-model="content" 
          class="form-control source-textarea"
          style="height: 400px; font-family: 'Courier New', monospace; font-size: 12px; line-height: 1.4;"
          placeholder="Edit raw HTML source here..."
        ></textarea>
      </div>
    </div>
    
    <QuillEditor
      v-if="!showSource"
      v-model:content="content"
      contentType="html"
      :options="editorOptions"
      @update:content="handleUpdate"
      style="height: 400px"
    />
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

// Props & Emits
const props = defineProps(['modelValue'])
const emit = defineEmits(['update:modelValue'])

// State
const content = ref(props.modelValue || '')
const showSource = ref(false)

// Editor options
const editorOptions = {
  modules: {
    toolbar: [
      [{ header: 1 }, { header: 2 }, { font: [] }],
      [{ size: ['small', false, 'large', 'huge'] }],
      [{ color: [] }, { background: [] }],
      ['bold', 'italic', 'underline', 'strike', 'blockquote'],
      [{ 'align': [] }, { 'direction': 'rtl' ? 'ltr' : false }],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      [{ 'indent': '-1'}, { 'indent': '+1' }],
      ['link', 'image', 'video'],
      ['clean']
    ],
    history: {
      delay: 1000,
      maxStack: 100,
      userOnly: true
    }
  }
}

// Methods
const copySource = () => {
  navigator.clipboard.writeText(content.value).then(() => {
    alert('Source code copied to clipboard!')
  }).catch(err => {
    console.error('Failed to copy: ', err)
  })
}

const handleKeydown = (event) => {
  if (event.key === 'Escape' && showSource.value) {
    showSource.value = false
  }
}

// Watch for external changes
watch(() => props.modelValue, (newVal) => {
  content.value = newVal || ''
})

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})

// Emit updates
const handleUpdate = (newContent) => {
  emit('update:modelValue', newContent)
}
</script>

<style scoped>
.source-container {
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  background-color: #f8f9fa;
}

.source-textarea {
  background-color: #f8f9fa !important;
  border: 1px solid #ced4da !important;
  resize: none;
}

.source-textarea:focus {
  box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
  border-color: #80bdff;
  outline: 0;
}
</style>