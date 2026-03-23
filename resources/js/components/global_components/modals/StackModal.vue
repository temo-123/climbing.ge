<template>
  <Teleport to="body">
    <div 
      v-if="modelValue" 
      class="stack-modal-overlay fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/85 backdrop-blur-md"
      @click.self="$emit('close')"
      :class="overlayClass"
    >
      <Transition name="modal">
        <div 
          ref="modalRef"
          class="stack-modal bg-white rounded-2xl shadow-2xl ring-1 ring-black/10 max-w-2xl w-full max-h-[95vh] overflow-hidden border border-gray-200/50"
          :class="modalClasses"
          :style="{ zIndex: computedZIndex }"
          role="dialog"
          aria-modal="true"
          tabindex="-1"
        >
          <!-- Header -->
          <div class="sticky top-0 z-20 flex items-center justify-between p-8 pb-6 bg-white border-b border-gray-200/50 rounded-2xl rounded-b-none shadow-sm">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">{{ title }}</h2>
            <button 
              @click="$emit('close')"
              class="text-gray-500 hover:text-gray-700 hover:bg-gray-200 transition-all p-2 -m-2 rounded-xl shadow-sm hover:shadow-md hover:scale-105 active:scale-95"
              type="button"
              aria-label="Close modal"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Content Slot -->
          <div class="p-8 max-h-[70vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-white hover:scrollbar-thumb-gray-400">
            <slot></slot>
          </div>

          <!-- Footer -->
          <div v-if="hasButtons" class="sticky bottom-0 z-20 flex flex-wrap items-center justify-end gap-4 p-8 pt-6 bg-white border-t border-gray-200/50 rounded-2xl rounded-t-none shadow-sm">
            <button
              v-if="cancelButton?.visible"
              @click="$emit('close')"
              :class="[
                'px-6 py-3 text-sm font-semibold rounded-xl transition-all duration-150 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 shadow-sm hover:shadow-md active:scale-95 border border-gray-200 hover:border-gray-300',
                cancelButton.btnClass || ''
              ].join(' ')"
            >
              {{ cancelButton.title || 'Cancel' }}
            </button>
            <button
              v-if="saveButton?.visible"
              type="button"
              :class="[
                'px-6 py-3 text-sm font-semibold rounded-xl transition-all duration-150 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 shadow-lg hover:shadow-xl active:scale-[0.97]',
                'bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 border border-transparent hover:border-indigo-500/50'
              ].join(' ')"
            >
              {{ saveButton.title || 'Save' }}
            </button>
          </div>
        </div>
      </Transition>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick, computed, reactive, watch } from 'vue'

const props = defineProps({
  modelValue: Boolean,
  title: {
    type: String,
    default: ''
  },
  size: {
    type: String,
    default: 'lg',
    validator: (value) => ['sm', 'md', 'lg', 'xl', 'xxl', 'xxxl', 'full', '90per', 'fullscreen'].includes(value)
  },
  borderless: {
    type: Boolean,
    default: false
  },
  modalClass: [String, Object],
  saveButton: {
    type: Object,
    default: () => ({ visible: true, title: 'Save' })
  },
  cancelButton: {
    type: Object,
    default: () => ({ visible: true, title: 'Cancel' })
  }
})

const emit = defineEmits(['close', 'update:modelValue'])

const modalRef = ref(null)

const modalStack = reactive(new Set())
let zIndexCounter = 9999

const computedZIndex = computed(() => {
  if (props.modelValue && !modalStack.has(props)) {
    modalStack.add(props)
  } else if (!props.modelValue && modalStack.has(props)) {
    modalStack.delete(props)
  }
  return zIndexCounter + modalStack.size * 10
})

const modalClasses = computed(() => {
  const sizeClass = props.size !== 'md' ? `modal-${props.size}` : 'modal-md'
  return {
    [`${sizeClass}`]: true,
    'modal-border-0': props.borderless,
    ...props.modalClass
  }
})

const stackPosition = computed(() => modalStack.size - 1 || 0)

const overlayClass = computed(() => ({
  'bg-black/85 backdrop-blur-md': true,
  [`modal-stack${stackPosition.value}`]: true
}))

const hasButtons = computed(() => props.saveButton?.visible || props.cancelButton?.visible)

onMounted(async () => {
  if (props.modelValue) {
    document.body.classList.add('stack-modal-open')
    await nextTick()
    modalRef.value?.focus()
  }
})

onUnmounted(() => {
  modalStack.delete(props)
  document.body.classList.remove('stack-modal-open')
})

watch(() => props.modelValue, (val) => {
  if (val) {
    document.body.classList.add('stack-modal-open')
  } else {
    document.body.classList.remove('stack-modal-open')
    emit('close')
  }
})

defineExpose({
  focus: () => modalRef.value?.focus()
})
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
  transform: translateY(-20px) scale(0.95);
}

.stack-modal {
  animation: modal-pop 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes modal-pop {
  0% {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
.modal-pop {
  animation-duration: 0.2s;
}

.stack-modal-overlay {
  position: fixed !important;
  inset: 0px !important;
  z-index: 99999 !important;
  /* display: flex !important; */
  align-items: center !important;
  justify-content: center !important;
  width: 100vw !important;
  height: 100vh !important;
  padding: 1rem !important;
  box-sizing: border-box !important;

  background-color: #000000d9;
}

.stack-modal {
  /* position: relative !important;
  margin: 0 auto !important; */
  max-width: 90vw !important;
  max-height: 95vh !important;
  width: auto !important;
  min-width: 320px !important;

  background-color: #f4f4f4;
  padding: 20px;
}

body.stack-modal-open {
  overflow: hidden !important;
}

body.stack-modal-open ::-webkit-scrollbar {
  display: none !important;
}

.modal-sm { max-width: 400px; }
.modal-md { max-width: 600px; }
.modal-lg { max-width: 800px; }
.modal-xl { max-width: 900px; }
.modal-xxl { max-width: 1040px; }
.modal-xxxl { max-width: 1180px; }
.modal-full {
  max-width: 100vw !important;
  width: 100vw !important;
  height: 100vh !important;
  max-height: none !important;
  border-radius: 0 !important;
}
.modal-full .p-8 { padding: 0 !important; }
.modal-90per { max-width: 90%; }
.modal-fullscreen {
  max-width: 100% !important;
  width: 100% !important;
  height: 100% !important;
}
.modal-fullscreen .p-8 { padding: 0 !important; }

@media (min-width: 768px) {
  .modal-sm { max-width: 400px; }
  .modal-md { max-width: 600px; }
  .modal-lg { max-width: 800px; }
  .modal-xl { max-width: 900px; }
  .modal-xxl { max-width: 1040px; }
  .modal-xxxl { max-width: 1180px; }
  .modal-90per { max-width: 90%; }
}

/* .modal-stack1 .stack-modal { transform: scale(1) translate(0, 0) !important; }
.modal-stack2 .stack-modal { transform: scale(0.95) translate(8px, 8px) !important; }
.modal-stack3 .stack-modal { transform: scale(0.92) translate(16px, 16px) !important; } */
</style>
