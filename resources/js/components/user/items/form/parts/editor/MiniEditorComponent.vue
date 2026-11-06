<template>
  <QuillEditor
    contentType="html"
    :options="editorOptions"
    @ready="on_ready"
    @update:content="on_update"
    style="height: 100px"
  />
</template>

<script setup>
import { QuillEditor } from '@vueup/vue-quill'
import { use_quill_html, add_toolbar_titles } from '../../../../../../services/editor/quillHtml'
import { useI18n } from 'vue-i18n'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

// Props & Emits
const props = defineProps(['modelValue'])
const emit = defineEmits(['update:modelValue'])

// State
// loads stored HTML through Quill's clipboard (see services/editor/quillHtml.js)
const { t } = useI18n()
const { content, on_ready: load_ready, on_update } = use_quill_html(props, emit)
const on_ready = (quill) => { load_ready(quill); add_toolbar_titles(quill, t) }

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

</script>