<template>
  <Teleport to="body">
    <div
      v-if="modelValue || show"
      class="stack-modal-overlay fixed inset-0 z-[99999] flex items-center justify-center p-2 sm:p-4 bg-black/85 backdrop-blur-md"
      @click.self="$emit('close')"
      :class="overlayClass"
    >
      <Transition name="modal">
        <div
          ref="modalRef"
          class="stack-modal bg-white rounded-2xl shadow-2xl ring-1 ring-black/10 flex flex-col mx-auto w-full max-w-6xl border border-gray-200/50"
          :class="[modalClasses]"
          :style="{ zIndex: computedZIndex }"
          role="dialog"
          aria-modal="true"
          tabindex="-1"
        >
          <!-- Header -->
          <div class="sticky top-0 z-20 flex-shrink-0 relative p-4 md:p-6 lg:p-8 pb-3 md:pb-4 lg:pb-6 bg-white border-b border-gray-200/50 rounded-2xl rounded-b-none shadow-sm">
            <button 
              @click="$emit('close')"
              class="close absolute" 
              type="button"
              aria-label="Close"
              style="font-size: 3.75rem; opacity: 0.5; line-height: 2; margin-right: 15px;"
            >
              <span aria-hidden="true">&times;</span>
            </button>

            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight pr-20"
              style="margin-left: 1cap;">{{ title }}</h2>
          </div>

          <!-- Content Slot -->
          <div class="flex-1 w-full max-w-full overflow-x-auto overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-white hover:scrollbar-thumb-gray-400 p-4 md:p-6 lg:p-8 modal-scrol">
            <slot></slot>
          </div>

          <!-- Footer -->
          <div v-if="hasButtons" class="sticky bottom-0 z-20 flex-shrink-0 bg-white border-t border-gray-200/50 rounded-2xl rounded-t-none shadow-sm">
            <slot name="footer">
              <div class="modal-footer p-4 md:p-6 lg:p-8 pt-3 md:pt-4 lg:pt-6">
                <button
                  v-if="cancelButton?.visible"
                  type="button"
                  class="btn btn-danger pull-left"
                  @click="$emit('close')"
                >
                  {{ cancelButton.title || 'Cancel' }}
                </button>
                <button
                  v-if="saveButton?.visible"
                  type="button"
                  class="btn btn-primary pull-right"
                  @click="$emit('save')"
                >
                  {{ saveButton.title || 'Save' }}
                </button>
              </div>
            </slot>
          </div>
        </div>
      </Transition>
    </div>
  </Teleport>
</template>

<script setup>
  import { ref, onMounted, onUnmounted, nextTick, computed, reactive, watch, useSlots } from 'vue'

  defineOptions({ inheritAttrs: false })

  const props = defineProps({
    modelValue: Boolean,
    show: Boolean,
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

  const emit = defineEmits(['update:modelValue', 'close', 'save'])

  const modalRef = ref(null)

  const isVisible = computed(() => props.modelValue || props.show)

  const modalStack = reactive(new Set())
  let zIndexCounter = 9999

  const computedZIndex = computed(() => {
    if (isVisible.value && !modalStack.has(props)) {
      modalStack.add(props)
    } else if (!isVisible.value && modalStack.has(props)) {
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

  const slots = useSlots()
  const hasButtons = computed(() => props.saveButton?.visible || props.cancelButton?.visible || !!slots.footer)

  onMounted(async () => {
    if (isVisible.value) {
      document.body.classList.add('stack-modal-open')
      document.body.style.overflow = 'hidden';

      await nextTick()
      modalRef.value?.focus()
    }
  })

  onUnmounted(() => {
    modalStack.delete(props)
    document.body.classList.remove('stack-modal-open')
    // document.body.style.overflow = 'auto';

  })

  watch(isVisible, (val) => {
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

<style>

  .modal-enter-active, .modal-leave-active {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .modal-enter-from, .modal-leave-to {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }

  /* .stack-modal {
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
  } */

  .stack-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100vw;
    height: 100vh;
    padding: 3vh 0;
    box-sizing: border-box;
    background-color: #000000d9;
  }

  .stack-modal {
    display: flex;
    flex-direction: column;
    align-self: center;
    height: auto;
    max-height: 94vh;
    min-height: 100px;
    width: clamp(320px, 90vw, 1400px);
    max-width: var(--modal-max-width, 720px);
    margin: 0 auto;
    background-color: #f4f4f4;
    box-sizing: border-box;
    overflow: hidden;
  }
  .modal-scrol{
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: #9ca3af transparent;
    padding: 3%;
  }
  .modal-scrol::-webkit-scrollbar {
    width: 6px;
  }
  .modal-scrol::-webkit-scrollbar-track {
    background: transparent;
  }
  .modal-scrol::-webkit-scrollbar-thumb {
    background-color: #9ca3af;
    border-radius: 3px;
  }
  .modal-scrol::-webkit-scrollbar-thumb:hover {
    background-color: #6b7280;
  }

  body.stack-modal-open {
    overflow: hidden !important;
  }

  body.stack-modal-open ::-webkit-scrollbar {
    display: none !important;
    overflow: hidden !important;
  }

  .stack-modal-open{
        overflow: hidden !important;
  }

  .modal-sm { --modal-max-width: 420px; width: 90% !important; max-width: 420px !important; }
  .modal-md { --modal-max-width: 560px; width: 90% !important; max-width: 560px !important; }
  .modal-lg { --modal-max-width: 720px; width: 90% !important; max-width: 720px !important; }
  .modal-xl { --modal-max-width: 900px; width: 90% !important; max-width: 900px !important; }
  .modal-xxl { --modal-max-width: 1100px; width: 90% !important; max-width: 1100px !important; }
  .modal-xxxl { --modal-max-width: 1300px; width: 90% !important; max-width: 1300px !important; }
  .modal-90per { width: 90vw; max-width: 90vw; }
  .modal-full, .modal-fullscreen {
    width: 100vw !important;
    height: 100vh !important;
    max-height: 100vh !important;
    border-radius: 0 !important;
    margin: 0 !important;
    max-width: none !important;
  }
  /* Remove overlay padding for fullscreen so modal fills edge-to-edge */
  .stack-modal-overlay:has(.modal-full),
  .stack-modal-overlay:has(.modal-fullscreen) {
    padding: 0 !important;
  }
  .modal-full .p-4, .modal-fullscreen .p-4,
  .modal-full .md\\:p-6, .modal-fullscreen .md\\:p-6,
  .modal-full .lg\\:p-8, .modal-fullscreen .lg\\:p-8 { padding: 0 !important; }

  @media (max-width: 640px) {
    .stack-modal { width: 100vw !important; border-radius: 0 0 1rem 1rem !important; }
  }
  @media (min-width: 768px) {
    .stack-modal { width: min(90vw, var(--modal-max-width, 720px)); }
    .modal-90per { width: 90vw; }
  }
</style>
