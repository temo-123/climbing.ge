# TabsComponent — Admin Data Table System

**Location:** `resources/js/components/user/items/data_table/TabsComponent.vue`  
**Used in:** Every admin list page on `user.climbing.ge`

---

## Overview

`TabsComponent` is the standard admin data table. Pass it an array of tab configs and it renders a tabbed, searchable, filterable, paginated table. Each tab is one dataset. All behavior is driven by the config — no custom HTML needed in the parent page.

---

## Quick Example

```html
<tabs-component
  :table_data="data_for_tab"
  :loading="loading"
  @update="load_data"
  @open_add="open_add_modal"
  @open_edit="open_edit_by_id"
  @confirm_delete="confirm_delete_by_id"
/>
```

```javascript
computed: {
  data_for_tab() {
    return [
      {
        id: 1,
        table_name: 'Summits',
        add_action: {
          action: 'fun',
          link: 'open_add',
          btn_title: '+ Add Summit',
          class: 'btn btn-primary'
        },
        tab_data: {
          data: this.summits,
          tab: {
            head: ['ID', 'Title', 'Height', 'Published', 'Edit', 'Delete'],
            body: [
              ['data', ['id']],
              ['data', ['title']],
              ['data', ['height']],
              ['data', ['published'], 'bool'],
              ['action_fun_id', 'open_edit', 'btn btn-sm btn-warning', '<i class="fa fa-pencil"></i>'],
              ['action_fun_id', 'confirm_delete', 'btn btn-sm btn-danger', '<i class="fa fa-trash"></i>'],
            ],
            perm: [['no'],['no'],['no'],['no'],['summit','edit'],['summit','del']]
          }
        }
      }
    ]
  }
}
```

---

## TabsComponent Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `table_data` | `Array` | `[]` | Array of tab config objects |
| `loading` | `Boolean` | `false` | Shows a spinner overlay when true |

---

## TabsComponent Events

| Event | Payload | When fired |
|---|---|---|
| `update` | — | "Refresh" button clicked |
| `delete_selected` | `Array<id>` | "Del selected items" clicked with checkboxes ticked |
| Any custom event | `id` or payload | Forwarded from cell actions and filter dropdowns |

Tab-level events (`add_action` with `action: 'fun'`) and cell-level events (`action_fun_id`) bubble up through `TabsComponent` to the parent. The parent handles them by name:

```javascript
// In parent template
@open_edit="open_edit_by_id"

// In parent methods
open_edit_by_id(id) {
  const item = this.items.find(x => x.id === id)
  this.selected = item
  this.edit_modal = true
}
```

---

## Tab Config Object

```javascript
{
  id: Number,                // unique tab identifier (used as radio value)
  table_name: String,        // tab label text
  list_page: String,         // optional: "Go to list" external link URL
  add_action: { ... },       // optional: "Add" button config (see below)
  filter_data: { ... },      // optional: filter dropdown config (see below)
  tab_data: {
    data: Array,             // the rows (each item must have an `id` field)
    tab: {
      head: Array<String>,   // column header labels
      body: Array<CellDef>,  // column definitions (one per head entry)
      perm: Array<PermDef>   // optional: column-level permission guards
    }
  }
}
```

---

## `add_action` Config

Controls the "Add New" button rendered above the table.

### `action: 'route'` — Vue Router link

```javascript
add_action: {
  action: 'route',
  link: 'articleAdd',          // named route
  params: { type: 'outdoor' }, // optional router params
  btn_title: 'Add Article',
  class: 'btn btn-primary'
}
```

### `action: 'url'` — External `<a>` link

```javascript
add_action: {
  action: 'url',
  link: 'https://example.com',
  btn_title: 'Open',
  class: 'btn btn-secondary'
}
```

### `action: 'fun'` / `'function'` — Emit custom event

```javascript
add_action: {
  action: 'fun',
  link: 'open_add_modal',   // event name emitted to parent
  btn_title: '+ Add',
  class: 'btn btn-primary'
}
```

The parent listens: `@open_add_modal="open_add_modal"` — called with no arguments.

> **Note:** `btn_title` is rendered with `{{ }}` text interpolation, not `v-html`. HTML tags in the title will appear as literal text. Use plain text only.

---

## Cell Definitions (`body` array)

Each entry in `body` describes one column. Entries are positionally matched to `head`.

### `['data', [field]]` — Display a field value

```javascript
['data', ['title']]           // row.title
['data', ['price']]           // row.price
```

### `['data', [field], 'bool']` — Boolean icon

Renders green ✓ (`fa-check`) for truthy values, red ✗ (`fa-times`) for falsy, orange ! (`fa-exclamation`) for null/undefined.

```javascript
['data', ['published'], 'bool']    // row.published shown as icon
['data', ['qr_code'], 'bool']
```

### `['data', [parentField, childField]]` — Nested object field

```javascript
['data', ['category', 'title']]    // row.category.title
['data', ['user', 'name']]         // row.user.name
```

If the nested value is a boolean, the bool icon is rendered automatically.

### `['data', [field1], [field2]]` — Two fields in one cell

```javascript
['data', ['first_name'], ['last_name']]   // row.first_name + ' ' + row.last_name
```

### `['data', [['obj1', 'key1'], ['obj2', 'key2']]]` — Two nested lookups

```javascript
['data', [['category', 'title'], ['subcategory', 'title']]]
// row.category.title + row.subcategory.title
```

### `['action_fun_id', eventName, class, html]` — Action button

Renders a `<button>`. On click emits `[eventName, row.id]` to the parent.

```javascript
['action_fun_id', 'open_edit', 'btn btn-sm btn-warning', '<i class="fa fa-pencil"></i>']
['action_fun_id', 'confirm_delete', 'btn btn-sm btn-danger', '<i class="fa fa-trash"></i>']
['action_fun_id', 'open_qr', 'btn btn-sm btn-info', 'QR']
```

The 4th element (`html`) is rendered with `v-html`, so Font Awesome `<i>` tags work here.

Parent receives: `open_edit(id)` → look up the full object by id.

### `['data_action_id', [field], eventName]` — Clickable text value

Renders the field value as clickable text. On click emits `[eventName, row.id]`.

```javascript
['data_action_id', ['url_title'], 'quick_view_action']
// shows row.url_title as a clickable span, emits quick_view_action with row.id
```

### `['action_router', routeName, class, html]` — Router link button

Renders a `<router-link>` navigating to a named route with `{ id: row.id }` params.

```javascript
['action_router', 'summitDetail', 'btn btn-sm btn-info', '<i class="fa fa-eye"></i>']
```

---

## Permission Guards (`perm` array)

Optional. One entry per column, positionally matched to `body`.

- `['no']` — always visible, no permission check
- `['subject', 'action']` — column is hidden unless `$can('action', 'subject')` is true

```javascript
perm: [
  ['no'],              // ID — always shown
  ['no'],              // Title — always shown
  ['no'],              // Published — always shown
  ['summit', 'edit'],  // Edit button — only if can('edit', 'summit')
  ['summit', 'del'],   // Delete button — only if can('del', 'summit')
]
```

> **Important:** `perm` array length must match `head` and `body` length exactly.

---

## Filter Dropdown (`filter_data`)

### Single filter

```javascript
filter_data: {
  title: 'Filter by Category',
  data: this.categories,        // array of option objects
  array_key: 'title',           // display field (supports 'nested.key')
  id_key: 'id',                 // value field
  action_fun: 'filter_by_category',  // event emitted on change (no ID)
  // OR:
  action_fun_id: 'filter_by_category',  // event emitted with selected ID
}
```

Parent events:
- `action_fun` → `@filter_by_category` (no argument)
- `action_fun_id` → `@filter_by_category(selectedId)`

### Multiple filters (array)

```javascript
filter_data: [
  {
    title: 'Category',
    data: this.categories,
    array_key: 'title',
    id_key: 'id',
    action_fun_id: 'filter_category'
  },
  {
    title: 'Status',
    data: [{ id: 1, title: 'Active' }, { id: 0, title: 'Inactive' }],
    array_key: 'title',
    id_key: 'id',
    action_fun_id: 'filter_status'
  }
]
```

Filters are laid out responsively: 2 filters → 50% each, 3 → 33% each, 4 → 25% each.

Parent receives: `@filter_category(selectedId)`, `@filter_status(selectedId)`.

---

## Sub-components

| Component | File | Role |
|---|---|---|
| `TabHeaderComponent` | `assets/TabHeaderComponent.vue` | Renders `<thead>` with column labels and select-all checkbox |
| `TabBodyComponent` | `assets/TabBodyComponent.vue` | Renders `<tbody>` rows with all cell types |
| `DataComponent` | `assets/DataComponent.vue` | Resolves field paths and renders bool icons |
| `FilterComponent` | `assets/FilterComponent.vue` | Dropdown filter UI |
| `PaginationComponent` | `assets/PaginationComponent.vue` | Page prev/next/jump controls + items-per-page selector |
| `SearchComponent` | `assets/SearchComponent.vue` | Text search input |

---

## Search & Pagination

- **Search** filters across all string fields of every row in the active tab using case-insensitive substring matching.
- **Pagination** options: 10, 20, 30, 50, 100, All. Defaults to 10 per page.
- Both reset to page 1 when the tab changes.
- Total item count for the active (filtered) tab is displayed below pagination controls.

---

## Bulk Delete

Checkboxes are rendered in the first column of every row. When any are checked, the "Del selected items" button becomes active. Clicking it emits `delete_selected` with an array of checked IDs and clears the selection.

```javascript
// Parent
@delete_selected="delete_multiple_items"

methods: {
  delete_multiple_items(ids) {
    axios.post('admin/bulk_delete', { ids }).then(() => this.load_data())
  }
}
```

---

## Full Working Example (Summit Admin Page)

```javascript
// SummitListPage.vue
computed: {
  data_for_tab() {
    return [
      {
        id: 1,
        table_name: 'Summits',
        add_action: { action: 'fun', link: 'open_add', btn_title: '+ Add Summit', class: 'btn btn-primary' },
        tab_data: {
          data: this.summits,
          tab: {
            head: ['ID', 'Title', 'KA Title', 'Height', 'Mount Route', 'QR', 'Published', 'QR Code', 'Edit', 'Delete'],
            body: [
              ['data', ['id']],
              ['data', ['title']],
              ['data', ['ka_title']],
              ['data', ['height']],
              ['data', ['mount_route_name']],
              ['data', ['qr_code'], 'bool'],
              ['data', ['published'], 'bool'],
              ['action_fun_id', 'open_qr', 'btn btn-sm btn-info', '<i class="fa fa-qrcode"></i>'],
              ['action_fun_id', 'open_edit', 'btn btn-sm btn-warning', '<i class="fa fa-pencil"></i>'],
              ['action_fun_id', 'confirm_delete', 'btn btn-sm btn-danger', '<i class="fa fa-trash"></i>'],
            ],
            perm: [['no'],['no'],['no'],['no'],['no'],['no'],['no'],['no'],['summit','edit'],['summit','del']]
          }
        }
      },
      {
        id: 2,
        table_name: 'Ascents',
        tab_data: {
          data: this.ascents,
          tab: {
            head: ['ID', 'Summit', 'User', 'Date', 'Delete'],
            body: [
              ['data', ['id']],
              ['data', ['summit_title']],
              ['data', ['user_name']],
              ['data', ['ascent_date']],
              ['action_fun_id', 'confirm_delete_ascent', 'btn btn-sm btn-danger', '<i class="fa fa-trash"></i>'],
            ],
            perm: [['no'],['no'],['no'],['no'],['summit','del']]
          }
        }
      }
    ]
  }
}
```

```html
<tabs-component
  :table_data="data_for_tab"
  :loading="loading"
  @update="load_data"
  @open_add="open_add = true"
  @open_edit="open_edit_by_id"
  @open_qr="open_qr_by_id"
  @confirm_delete="confirm_delete_by_id"
  @confirm_delete_ascent="confirm_delete_ascent_by_id"
/>
```

```javascript
methods: {
  open_edit_by_id(id) {
    this.selected_summit = this.summits.find(s => s.id === id)
    this.edit_modal = true
  },
  confirm_delete_by_id(id) {
    if (confirm('Delete?')) {
      axios.delete('get_summit_admin/delete/' + id)
        .then(() => this.load_data())
    }
  }
}
```

---

[Go back](../FRONTEND.md)
