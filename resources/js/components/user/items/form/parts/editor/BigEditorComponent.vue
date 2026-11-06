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

    <div v-if="!showSource" ref="wrapRef" class="cg-editor-wrap">
      <QuillEditor
        ref="editorRef"
        @ready="onEditorReady"
        contentType="html"
        :options="editorOptions"
        @update:content="on_update"
        style="height: 400px"
      />

      <!-- Table size picker: opens under the toolbar's table button -->
      <div
        v-if="picker.open"
        ref="pickerRef"
        class="cg-table-picker shadow-sm"
        :style="{ top: picker.top + 'px', left: picker.left + 'px' }"
        role="dialog"
        :aria-label="t('admin.editor.tools.table')"
        @mouseleave="picker.rows = 0; picker.cols = 0"
      >
        <div class="cg-table-picker__grid" :style="{ gridTemplateColumns: 'repeat(' + PICKER_COLS + ', 18px)' }">
          <button
            v-for="cell in PICKER_ROWS * PICKER_COLS"
            :key="cell"
            type="button"
            class="cg-table-picker__cell"
            :class="{ 'is-on': cellRow(cell) <= picker.rows && cellCol(cell) <= picker.cols }"
            :aria-label="cellRow(cell) + ' × ' + cellCol(cell)"
            @mousedown.prevent
            @mouseenter="picker.rows = cellRow(cell); picker.cols = cellCol(cell)"
            @focus="picker.rows = cellRow(cell); picker.cols = cellCol(cell)"
            @click="insertTable(cellRow(cell), cellCol(cell))"
          ></button>
        </div>
        <div class="cg-table-picker__label">
          {{ picker.rows ? t('admin.editor.table.size', { rows: picker.rows, cols: picker.cols }) : t('admin.editor.table.pick_size') }}
        </div>
      </div>

      <!-- Cell tools: float over the table while the cursor is in it -->
      <div
        v-if="cellBar.show"
        class="cg-table-tools shadow-sm"
        :style="{ top: cellBar.top + 'px', left: cellBar.left + 'px' }"
        role="toolbar"
        :aria-label="t('admin.editor.tools.table')"
      >
        <!-- width -->
        <div class="cg-table-tools__menu">
          <button type="button" class="cg-table-tools__btn cg-table-tools__btn--label"
            :class="{ 'is-open': widthMenu }" aria-haspopup="true" :aria-expanded="widthMenu ? 'true' : 'false'"
            :title="t('admin.editor.table.width')" :aria-label="t('admin.editor.table.width')"
            @mousedown.prevent @click="widthMenu = !widthMenu">
            <span v-html="WIDTH_ICON"></span>
            <span>{{ widthLabel(cellBar.width) }}</span>
          </button>
          <div v-if="widthMenu" class="cg-table-tools__dropdown shadow-sm" role="menu">
            <button v-for="w in WIDTH_OPTIONS" :key="w || 'full'" type="button" role="menuitemradio"
              class="cg-table-tools__option" :class="{ 'is-active': cellBar.width === w }"
              :aria-checked="cellBar.width === w ? 'true' : 'false'"
              @mousedown.prevent @click="setTableWidth(w)">
              <i class="fa fa-check" aria-hidden="true"></i> {{ widthLabel(w) }}
            </button>
          </div>
        </div>

        <button v-for="tool in TABLE_TOOLS" :key="tool.action" type="button"
          class="cg-table-tools__btn" :class="{ 'is-danger': tool.danger, 'has-gap': tool.gap }"
          :title="t('admin.editor.table.' + tool.label)" :aria-label="t('admin.editor.table.' + tool.label)"
          @mousedown.prevent @click="tableAction(tool.action)"
          v-html="tool.icon"></button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { QuillEditor } from '@vueup/vue-quill'
import { use_quill_html, add_toolbar_titles, TABLE_WIDTHS } from '../../../../../../services/editor/quillHtml'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

// Props & Emits
const props = defineProps(['modelValue'])
const emit = defineEmits(['update:modelValue'])
const { t } = useI18n()

// State
const showSource = ref(false)
// loads stored HTML through Quill's clipboard (see services/editor/quillHtml.js)
const { content, on_ready: load_ready, on_update } = use_quill_html(props, emit, showSource)
const editorRef = ref(null)
const wrapRef = ref(null)
const pickerRef = ref(null)

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
        ['link', 'image', 'video', 'table'],
        ['clean']
      ],
      handlers: {
        image: imageUploadHandler,
        table: togglePicker,
      }
    },
    // Quill 2 built-in tables (also keeps <table> HTML pasted / typed in source mode)
    table: true,
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

// ---- Tables ----
// Quill 2's table module has no UI of its own: the toolbar's table button (Quill's own
// icon) opens a size picker; while the cursor is in a cell a small tool bar floats over
// the table. Buttons use mousedown.prevent so the cursor stays in the editor.
const PICKER_ROWS = 8
const PICKER_COLS = 8
const cellRow = (cell) => Math.ceil(cell / PICKER_COLS)
const cellCol = (cell) => ((cell - 1) % PICKER_COLS) + 1

const svg = (body) => `<svg viewBox="0 0 18 18" aria-hidden="true">${body}</svg>`
const plus = (x, y) => `<path d="M${x - 2} ${y}h4M${x} ${y - 2}v4"/>`
const TABLE_TOOLS = [
  { action: 'insertRowAbove', label: 'row_above', icon: svg('<rect x="3" y="8" width="12" height="7" rx="1"/><path d="M3 11.5h12"/>' + plus(9, 4)) },
  { action: 'insertRowBelow', label: 'row_below', icon: svg('<rect x="3" y="3" width="12" height="7" rx="1"/><path d="M3 6.5h12"/>' + plus(9, 14)) },
  { action: 'insertColumnLeft', label: 'column_left', icon: svg('<rect x="8" y="3" width="7" height="12" rx="1"/><path d="M11.5 3v12"/>' + plus(4, 9)) },
  { action: 'insertColumnRight', label: 'column_right', icon: svg('<rect x="3" y="3" width="7" height="12" rx="1"/><path d="M6.5 3v12"/>' + plus(14, 9)) },
  { action: 'deleteRow', label: 'delete_row', danger: true, gap: true, icon: svg('<rect x="3" y="6" width="12" height="6" rx="1"/><path d="M7 7.5l4 3M11 7.5l-4 3"/>') },
  { action: 'deleteColumn', label: 'delete_column', danger: true, icon: svg('<rect x="6" y="3" width="6" height="12" rx="1"/><path d="M7.5 7l3 4M10.5 7l-3 4"/>') },
  { action: 'deleteTable', label: 'delete_table', danger: true, gap: true, icon: svg('<path d="M4 5h10M7.5 5V3.5h3V5M5.5 5l.7 9.5h5.6l.7-9.5"/><path d="M8 8v4M10 8v4"/>') },
]

const picker = reactive({ open: false, top: 0, left: 0, rows: 0, cols: 0 })
const cellBar = reactive({ show: false, top: 0, left: 0, width: null })
const widthMenu = ref(false)

// Table width (format 'table-width' on every cell; null = full width, the default)
const WIDTH_OPTIONS = [null, ...TABLE_WIDTHS]
const WIDTH_ICON = svg('<path d="M2 9h14M4.5 6.5L2 9l2.5 2.5M13.5 6.5L16 9l-2.5 2.5"/>')
const widthLabel = (w) => w === null ? t('admin.editor.table.width_full')
  : w === 'fit' ? t('admin.editor.table.width_fit') : w + '%'

function currentTable() {
  const table = tableModule()
  const range = quillRef && quillRef.getSelection()
  return table && range ? table.getTable(range)[0] : null
}

function readTableWidth(tableBlot) {
  const cell = tableBlot && tableBlot.domNode.querySelector('td')
  const match = cell && /(?:^|\s)ql-tw-(\S+)/.exec(cell.className)
  return match && TABLE_WIDTHS.includes(match[1]) ? match[1] : null
}

// format every cell of the table (rows added later get it re-applied in tableAction)
function applyTableWidth(tableBlot, width) {
  quillRef.formatLine(quillRef.getIndex(tableBlot), tableBlot.length(), 'table-width', width || false, 'user')
}

function setTableWidth(width) {
  const tableBlot = currentTable()
  widthMenu.value = false
  if (!tableBlot) return
  applyTableWidth(tableBlot, width)
  updateCellBar()
}
let quillRef = null
let savedRange = null

const tableModule = () => (quillRef ? quillRef.getModule('table') : null)

// position of an element relative to the editor wrapper
function relRect(el) {
  const wrap = wrapRef.value.getBoundingClientRect()
  const r = el.getBoundingClientRect()
  return { top: r.top - wrap.top, left: r.left - wrap.left, bottom: r.bottom - wrap.top, right: r.right - wrap.left, width: r.width }
}

function togglePicker() {
  if (picker.open) return closePicker()
  const button = wrapRef.value && wrapRef.value.querySelector('.ql-toolbar .ql-table')
  if (!button) return
  savedRange = quillRef.getSelection(true)
  const r = relRect(button)
  const width = PICKER_COLS * 20 + 16
  picker.top = r.bottom + 4
  picker.left = Math.max(0, Math.min(r.left, wrapRef.value.clientWidth - width))
  picker.rows = 0
  picker.cols = 0
  picker.open = true
}

function closePicker() {
  picker.open = false
}

function insertTable(rows, cols) {
  const table = tableModule()
  if (!table) return
  // cursor where it was when the picker opened (editor never clicked → end of text)
  let range = savedRange || { index: Math.max(0, quillRef.getLength() - 1), length: 0 }
  // Quill can't nest tables, and merges a table that directly follows another one.
  // Inside a table: add two plain lines after it — a spacer that keeps the tables apart,
  // and the line the new table is inserted in front of.
  const [inside] = table.getTable(range)
  if (inside) {
    const after = quillRef.getIndex(inside) + inside.length()
    quillRef.insertText(after, '\n\n', 'user')
    quillRef.formatLine(after, 2, 'table', false, 'user')
    range = { index: after + 1, length: 0 }
  }
  quillRef.setSelection(range, 'silent')
  table.insertTable(rows, cols)
  closePicker()
  quillRef.focus()
  updateCellBar()
}

function tableAction(action) {
  const table = tableModule()
  if (!table || typeof table[action] !== 'function') return
  const width = readTableWidth(currentTable())
  widthMenu.value = false
  table[action]()
  // new rows / columns come without the width class
  const tableBlot = action !== 'deleteTable' && currentTable()
  if (tableBlot && width) applyTableWidth(tableBlot, width)
  updateCellBar()
}

// float the cell tools just above the table the cursor is in (below its top edge
// when the table starts above the visible area)
function updateCellBar() {
  const table = tableModule()
  const range = quillRef && quillRef.getSelection()
  const [tableBlot] = table && range ? table.getTable(range) : [null]
  if (!tableBlot || !wrapRef.value) {
    cellBar.show = false
    widthMenu.value = false
    return
  }
  cellBar.width = readTableWidth(tableBlot)
  const box = relRect(tableBlot.domNode)
  const area = relRect(quillRef.container)
  const BAR_HEIGHT = 34
  if (box.bottom < area.top || box.top > area.bottom) {
    cellBar.show = false
    return
  }
  let top = box.top - BAR_HEIGHT - 4
  if (top < area.top + 2) top = Math.max(area.top + 2, Math.min(box.top + 2, area.bottom - BAR_HEIGHT - 2))
  cellBar.top = top
  cellBar.left = Math.max(area.left + 2, box.left)
  cellBar.show = true
}

function onDocumentMousedown(event) {
  if (widthMenu.value && !(event.target.closest && event.target.closest('.cg-table-tools__menu'))) widthMenu.value = false
  if (!picker.open) return
  const inPicker = pickerRef.value && pickerRef.value.contains(event.target)
  const onButton = event.target.closest && event.target.closest('.ql-table')
  if (!inPicker && !onButton) closePicker()
}

function onEditorReady(quill) {
  quillRef = quill
  load_ready(quill)
  add_toolbar_titles(quill, t)
  quill.on('selection-change', () => nextFrame(updateCellBar))
  quill.on('text-change', () => nextFrame(updateCellBar))
  quill.root.addEventListener('scroll', () => nextFrame(updateCellBar), { passive: true })
}

const nextFrame = (fn) => window.requestAnimationFrame(fn)

// Methods
const copySource = () => {
  navigator.clipboard.writeText(content.value).then(() => {
    alert(t('admin.editor.source_copied_to_clipboard'))
  }).catch(err => {
    console.error('Failed to copy: ', err)
  })
}

const handleKeydown = (event) => {
  if (event.key !== 'Escape') return
  if (picker.open) return closePicker()
  if (widthMenu.value) { widthMenu.value = false; return }
  if (showSource.value) showSource.value = false
}

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
  document.addEventListener('mousedown', onDocumentMousedown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
  document.removeEventListener('mousedown', onDocumentMousedown)
})

</script>

<style scoped>
.cg-editor-wrap {
  position: relative;
}

/* size picker — looks like a Quill picker dropdown */
.cg-table-picker {
  position: absolute;
  z-index: 20;
  padding: 8px;
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.cg-table-picker__grid {
  display: grid;
  gap: 2px;
}
.cg-table-picker__cell {
  width: 18px;
  height: 18px;
  padding: 0;
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 2px;
  cursor: pointer;
}
.cg-table-picker__cell.is-on {
  background: rgba(var(--cg-accent-rgb), .18);
  border-color: var(--cg-accent);
}
.cg-table-picker__label {
  margin-top: 6px;
  font-size: 12px;
  color: #444;
  text-align: center;
}

/* cell tools — same look as the Quill toolbar (icon buttons, #444 → accent on hover) */
.cg-table-tools {
  position: absolute;
  z-index: 15;
  display: flex;
  align-items: center;
  gap: 2px;
  padding: 3px 4px;
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.cg-table-tools__btn {
  width: 28px;
  height: 26px;
  padding: 3px 5px;
  background: none;
  border: 0;
  border-radius: 3px;
  color: #444;
  cursor: pointer;
}
.cg-table-tools__btn.has-gap {
  margin-left: 6px;
}
.cg-table-tools__btn :deep(svg) {
  width: 18px;
  height: 18px;
  fill: none;
  stroke: currentColor;
  stroke-width: 1.4;
  stroke-linecap: round;
  stroke-linejoin: round;
}
.cg-table-tools__btn:hover,
.cg-table-tools__btn:focus-visible {
  color: var(--cg-accent);
  background: rgba(var(--cg-accent-rgb), .08);
  outline: none;
}
.cg-table-tools__btn--label {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  width: auto;
  padding: 3px 7px;
  font-size: 12px;
  white-space: nowrap;
}
.cg-table-tools__btn--label :deep(span) {
  display: inline-flex;
}
.cg-table-tools__btn.is-open {
  color: var(--cg-accent);
  background: rgba(var(--cg-accent-rgb), .08);
}
.cg-table-tools__menu {
  position: relative;
  margin-right: 6px;
}
.cg-table-tools__dropdown {
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  z-index: 25;
  min-width: 150px;
  padding: 4px 0;
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.cg-table-tools__option {
  display: block;
  width: 100%;
  padding: 5px 12px;
  background: none;
  border: 0;
  color: #444;
  font-size: 13px;
  text-align: left;
  cursor: pointer;
}
.cg-table-tools__option .fa {
  width: 14px;
  visibility: hidden;
}
.cg-table-tools__option.is-active .fa {
  visibility: visible;
  color: var(--cg-accent);
}
.cg-table-tools__option:hover,
.cg-table-tools__option:focus-visible {
  background: rgba(var(--cg-accent-rgb), .08);
  color: var(--cg-accent);
  outline: none;
}
.cg-table-tools__btn.is-danger:hover,
.cg-table-tools__btn.is-danger:focus-visible {
  color: #c0392b;
  background: rgba(192, 57, 43, .08);
}

.source-container {
  border: 1px solid var(--cg-line);
  border-radius: 0.375rem;
  background-color: var(--cg-chalk);
}

.source-textarea {
  background-color: var(--cg-chalk) !important;
  border: 1px solid var(--cg-line) !important;
  resize: none;
}

.source-textarea:focus {
  box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
  border-color: #80bdff;
  outline: 0;
}
</style>
