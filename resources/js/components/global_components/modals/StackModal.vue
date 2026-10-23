<template>
  <Teleport to="body">
    <div
      v-if="modelValue || show"
      class="stack-modal-overlay"
      @click.self="$emit('close')"
      :class="overlayClass"
    >
      <Transition name="modal">
        <div
          ref="modalRef"
          class="stack-modal"
          :class="[modalClasses]"
          :style="{ zIndex: computedZIndex }"
          role="dialog"
          aria-modal="true"
          tabindex="-1"
        >
          <!-- Header -->
          <div class="stack-modal__header">
            <h2 class="stack-modal__title">{{ title }}</h2>
            <button
              @click="$emit('close')"
              class="stack-modal__close"
              type="button"
              :aria-label="t('common.close')"
            >
              <i class="fa fa-times" aria-hidden="true"></i>
            </button>
          </div>

          <!-- Content Slot -->
          <div class="modal-scrol stack-modal__body" :class="{ 'modal-scrol--user': isUserPage }">
            <slot></slot>
          </div>

          <!-- Footer -->
          <div v-if="hasButtons" class="stack-modal__footer">
            <slot name="footer">
              <div class="modal-footer">
                <button
                  v-if="cancelButton?.visible"
                  type="button"
                  class="btn btn-light me-auto"
                  @click="$emit('close')"
                >
                  {{ cancelButton.title || t('common.cancel') }}
                </button>
                <button
                  v-if="saveButton?.visible"
                  type="button"
                  class="btn btn-primary"
                  @click="$emit('save')"
                >
                  <i class="fa fa-check" aria-hidden="true"></i>
                  {{ saveButton.title || t('common.save') }}
                </button>
              </div>
            </slot>
          </div>
        </div>
      </Transition>
    </div>
  </Teleport>
</template>

<script>
  // Module scope (shared across every <StackModal> instance/import) — this is
  // deliberately OUTSIDE <script setup>, which runs fresh per component
  // instance and would otherwise give each open modal its own private stack,
  // making them unable to see each other and stack their z-index correctly.
  // That's what broke nested modals (e.g. an upload/crop popup opened from
  // inside an already-open modal): with a per-instance stack, two modals
  // could get the same z-index, so a click meant for the inner one could
  // land on the outer modal's overlay and close it instead.
  import { reactive } from 'vue'
  const modalStack = reactive(new Set())
  const zIndexCounter = 9999
</script>

<script setup>
  import { ref, onMounted, onUnmounted, nextTick, computed, watch, useSlots } from 'vue'
  import { useI18n } from 'vue-i18n'

  defineOptions({ inheritAttrs: false })

  const { t } = useI18n()

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
      type: [Object, Boolean],
      default: () => ({ visible: true })
    },
    cancelButton: {
      type: [Object, Boolean],
      default: () => ({ visible: true })
    }
  })

  const emit = defineEmits(['update:modelValue', 'close', 'save'])

  const modalRef = ref(null)

  const isUserPage = window.location.hostname === process.env.MIX_USER_PAGE_URL

  const isVisible = computed(() => props.modelValue || props.show)

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
    document.body.style.overflow = '';
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

  .stack-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100vw;
    height: 100vh;
    height: 100svh;
    padding: 3vh 0;
    box-sizing: border-box;
    background-color: rgba(21, 33, 43, .72);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
  }

  .stack-modal {
    display: flex;
    flex-direction: column;
    align-self: center;
    height: auto;
    max-height: 94vh;
    max-height: 94svh;
    min-height: 100px;
    width: clamp(320px, 90vw, 1400px);
    max-width: var(--modal-max-width, 720px);
    margin: 0 auto;
    background-color: #fff;
    border-radius: var(--cg-r-xl, 1.25rem);
    box-shadow: 0 30px 80px -30px rgba(0, 0, 0, .55);
    box-sizing: border-box;
    overflow: hidden;
  }

  /* Header: chalk strip with a topo texture + accent ridge, same family as the site header */
  .stack-modal__header {
    position: relative;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.1rem 1.25rem 1.1rem 1.5rem;
    background-color: var(--cg-chalk, #f5f3ee);
    background-image: var(--cg-topo-dark);
    background-size: 380px;
    border-bottom: 1px solid var(--cg-line, #e3ded4);
  }
  .stack-modal__header::after {
    content: "";
    position: absolute;
    left: 1.5rem;
    bottom: -2px;
    width: 56px;
    height: 3px;
    border-radius: 3px;
    background: linear-gradient(90deg, var(--cg-accent) 0 72%, var(--cg-hold) 72% 100%);
  }
  .stack-modal__title {
    flex: 1;
    min-width: 0;
    margin: 0;
    font-size: clamp(1.35rem, 2.6vw, 1.8rem);
    line-height: 1.15;
    color: var(--cg-ink, #15212b);
  }
  .stack-modal__close {
    display: inline-grid;
    place-items: center;
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border: 1px solid var(--cg-line, #e3ded4);
    border-radius: 50%;
    background: #fff;
    color: var(--cg-stone, #56646e);
    font-size: 1.05rem;
    cursor: pointer;
    transition: transform .2s ease, color .2s ease, background-color .2s ease;
  }
  .stack-modal__close:hover {
    transform: rotate(90deg);
    color: var(--cg-ink, #15212b);
    background: var(--cg-chalk-2, #ebe7df);
  }
  .stack-modal__footer {
    flex-shrink: 0;
    background: #fff;
    border-top: 1px solid var(--cg-line, #e3ded4);
  }
  .stack-modal__footer .modal-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: .5rem;
    padding: .9rem 1.5rem;
    border: 0;
  }
  .modal-scrol{
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: #9ca3af transparent;
    padding: 1.5rem;
  }
  @media (max-width: 575.98px) {
    .modal-scrol { padding: 1rem; }
  }
  .modal-scrol--user {
    font-size: 0.875rem;
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

  /* Compound selectors (.stack-modal.modal-xxx) so these always outrank any
     third-party CSS that happens to reuse bare "modal-lg"/"modal-fullscreen"
     class names on the page (e.g. Bootstrap 3, loaded globally on the public
     guidebook layout) — a bare-class rule can never out-specificity a
     two-class compound selector, regardless of load order or !important. */
  .stack-modal.modal-sm  { --modal-max-width: 420px;  width: 90% !important; max-width: 420px  !important; }
  .stack-modal.modal-md  { --modal-max-width: 560px;  width: 90% !important; max-width: 560px  !important; }
  .stack-modal.modal-lg  { --modal-max-width: 720px;  width: 90% !important; max-width: 720px  !important; }
  .stack-modal.modal-xl  { --modal-max-width: 900px;  width: 90% !important; max-width: 900px  !important; }
  .stack-modal.modal-xxl { --modal-max-width: 1100px; width: 90% !important; max-width: 1100px !important; }
  .stack-modal.modal-xxxl{ --modal-max-width: 1300px; width: 90% !important; max-width: 1300px !important; }
  .stack-modal.modal-90per { width: 90vw !important; max-width: 90vw !important; }
  /* full = near full-screen with small side margins */
  .stack-modal.modal-full {
    width: calc(100vw - 10px) !important;
    height: calc(100vh - 10px) !important;
    height: calc(100svh - 10px) !important;
    max-height: calc(100vh - 10px) !important;
    max-height: calc(100svh - 10px) !important;
    border-radius: 8px !important;
    margin: 5px !important;
    max-width: none !important;
  }
  .stack-modal-overlay:has(.stack-modal.modal-full) {
    padding: 0 !important;
  }

  /* fullscreen = true edge-to-edge, no chrome */
  .stack-modal.modal-fullscreen {
    width: 100vw !important;
    height: 100vh !important;
    height: 100svh !important;
    max-height: 100vh !important;
    max-height: 100svh !important;
    border-radius: 0 !important;
    margin: 0 !important;
    max-width: none !important;
  }
  .stack-modal-overlay:has(.stack-modal.modal-fullscreen) {
    padding: 0 !important;
  }

  @media (max-width: 640px) {
    /* Mobile browsers report 100vh as the layout viewport (as if their own
       address/nav bar were hidden), taller than what's actually visible, which
       pushed the sticky footer/buttons below the real visible area on tall
       modals. svh (small viewport height) always assumes that browser chrome
       IS showing, so sizing against it guarantees the footer/buttons stay
       above the browser's own bottom bar instead of hiding behind it -
       dvh would still leave them tucked behind the bar whenever it's visible.
       Safe-area insets additionally clear the OS home-indicator/notch area. */
    .stack-modal-overlay {
      padding: max(8px, env(safe-area-inset-top)) 0 max(8px, env(safe-area-inset-bottom));
    }
    .stack-modal {
      width: 100vw !important;
      max-height: calc(100vh - 16px) !important;
      max-height: calc(100svh - 16px) !important;
      border-radius: 0 0 1.25rem 1.25rem !important;
    }
    .modal-footer {
      padding-bottom: calc(1rem + env(safe-area-inset-bottom)) !important;
    }
  }
  @media (min-width: 768px) {
    .stack-modal { width: min(90vw, var(--modal-max-width, 720px)); }
    .modal-90per { width: 90vw; }
  }
</style>
