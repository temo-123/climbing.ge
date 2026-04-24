# StackModal — Reusable Modal Component

**Location:** `resources/js/components/global_components/modals/StackModal.vue`  
**Global tag:** `<stack-modal>`  
**Registered in:** `resources/js/app.js`

---

## Overview

`StackModal` is the universal modal used across all subdomains and all admin panels. It teleports to `<body>`, supports nested stacking with auto z-index management, and provides a scrollable content area with sticky header and footer.

---

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `show` | `Boolean` | — | Controls visibility (legacy pattern) |
| `modelValue` | `Boolean` | — | Controls visibility (v-model pattern) |
| `title` | `String` | `''` | Modal header title text |
| `size` | `String` | `'lg'` | Modal width size (see size table below) |
| `borderless` | `Boolean` | `false` | Removes inner border styling |
| `modalClass` | `String\|Object` | — | Extra CSS class(es) on the modal box |
| `saveButton` | `Object` | `{ visible: true, title: 'Save' }` | Save button config |
| `cancelButton` | `Object` | `{ visible: true, title: 'Cancel' }` | Cancel/close button config |

### Size values

| `size` | Max width |
|---|---|
| `sm` | 420 px |
| `md` | 560 px |
| `lg` *(default)* | 720 px |
| `xl` | 900 px |
| `xxl` | 1100 px |
| `xxxl` | 1300 px |
| `90per` | 90 vw |
| `full` / `fullscreen` | 100 vw × 100 vh |

---

## Events

| Event | Payload | When fired |
|---|---|---|
| `close` | — | Overlay click, × button, or `cancelButton` click |
| `update:modelValue` | — | Emitted on close for v-model support |
| `save` | — | Defined but NOT wired to the default save button (wire it manually — see usage) |

> **Note:** The default save button in the footer renders but does **not** emit `save` automatically. Wire it via the `#footer` slot if you need a save action.

---

## Slots

| Slot | Description |
|---|---|
| *(default)* | Modal body content — scrollable area |
| `#footer` | Replaces the entire footer. If provided, the default Cancel/Save buttons are hidden |

The footer is only rendered when `saveButton.visible`, `cancelButton.visible`, or the `#footer` slot is used.

---

## Usage

### Basic — `show` prop pattern (most common in this codebase)

```html
<stack-modal
  :show="modal_open"
  title="Edit Product"
  @close="modal_open = false"
>
  <p>Modal content here</p>
</stack-modal>
```

### v-model pattern

```html
<stack-modal v-model="modal_open" title="Edit Product" @close="modal_open = false">
  <p>Modal content here</p>
</stack-modal>
```

### Custom footer with save action

```html
<stack-modal
  :show="modal_open"
  title="Add Summit"
  size="xl"
  :saveButton="{ visible: false }"
  :cancelButton="{ visible: false }"
  @close="modal_open = false"
>
  <form @submit.prevent="save">
    <!-- form fields -->
  </form>

  <template #footer>
    <div class="modal-footer">
      <button class="btn btn-danger pull-left" @click="modal_open = false">Cancel</button>
      <button class="btn btn-primary pull-right" @click="save">Save</button>
    </div>
  </template>
</stack-modal>
```

### Without any footer buttons

```html
<stack-modal
  :show="modal_open"
  title="QR Code"
  size="md"
  :saveButton="{ visible: false }"
  :cancelButton="{ visible: false }"
  @close="modal_open = false"
>
  <img :src="qr_url" />
</stack-modal>
```

---

## Stacking (nested modals)

Multiple modals can be open simultaneously. Each instance tracks its position in a reactive `modalStack` Set and computes a unique `z-index` (base 9999, +10 per layer). Opening a modal inside another modal automatically places it on top.

```html
<!-- Outer modal -->
<stack-modal :show="outer_open" title="Select Item" @close="outer_open = false">
  <button @click="inner_open = true">Open Detail</button>

  <!-- Inner modal — stacks on top automatically -->
  <stack-modal :show="inner_open" title="Item Detail" @close="inner_open = false">
    <p>Detail content</p>
  </stack-modal>
</stack-modal>
```

---

## Behavior Notes

- Uses `<Teleport to="body">` — renders outside the component tree, no z-index conflicts with parent containers.
- When visible, adds `stack-modal-open` class to `<body>` and sets `overflow: hidden` to prevent background scroll.
- Entry animation: fade + slide up (`translateY(-20px) scale(0.95)`) over 200 ms.
- On mobile (`< 640 px`), the modal stretches full width with only bottom corners rounded.
- Clicking the overlay backdrop calls `$emit('close')`.
- The `×` close button in the header also calls `$emit('close')`.

---

[Go back](../FRONTEND.md)
