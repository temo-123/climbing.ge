<template>
  <div>
    <button
      type="button"
      @click="showSource = !showSource"
      class="btn btn-secondary mb-2"
      :class="{ 'btn-success': showSource }"
    >
      {{ showSource ? t('admin.editor.hide_source_btn') : t('admin.editor.check_source_btn') }}
    </button>

    <div v-if="showSource" class="source-container mb-3">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">{{ t('admin.editor.html_source_code_title') }}</h6>
        <button
          type="button"
          @click="copySource"
          class="btn btn-secondary btn-sm"
        >
          {{ t('admin.editor.copy_to_clipboard_btn') }}
        </button>
      </div>
      <div style="max-height: 400px; overflow-y: auto;">
        <textarea
          v-model="content"
          class="form-control source-textarea"
          style="height: 400px; font-family: 'Courier New', monospace; font-size: 12px; line-height: 1.4;"
          :placeholder="t('admin.editor.edit_raw_html_placeholder')"
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
import { useI18n } from 'vue-i18n'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

// Props & Emits
const props = defineProps(['modelValue'])
const emit = defineEmits(['update:modelValue'])
const { t } = useI18n()

// State
const content = ref(props.modelValue || '')
const showSource = ref(false)

// Editor options
const editorOptions = {
  modules: {
    toolbar: {
      container: [
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
      handlers: {
        image: imageUploadHandler,
      }
    },
    history: {
      delay: 1000,
      maxStack: 100,
      userOnly: true
    }
  }
}

// Secure image upload to the server
function imageUploadHandler() {
  const input = document.createElement('input')
  input.type = 'file'
  input.accept = 'image/jpeg,image/png,image/gif,image/webp'
  input.click()
  input.onchange = async () => {
    const file = input.files[0]
    if (!file) return
    const fd = new FormData()
    fd.append('image', file)
    try {
      const { data } = await axios.post('/upload/editor-image', fd)
      const quill = document.querySelector('.ql-editor')
      const range = window.getSelection()
      // Insert image at current cursor
      const img = `<img src="${data.url}" />`
      document.execCommand('insertHTML', false, img)
    } catch {
      alert(t('admin.editor.image_upload_failed'))
    }
  }
}

// Methods
const copySource = () => {
  navigator.clipboard.writeText(content.value).then(() => {
    alert(t('admin.editor.source_copied_to_clipboard'))
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
