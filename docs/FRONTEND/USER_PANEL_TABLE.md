# Admin Panel Data Table System

Every admin list page in the `user.climbing.ge` dashboard (articles, comments, orders, users, roles, etc.) renders through one shared, generic, tabbed data-table component pair — `TabsComponent.vue` + its child `assets/TabBodyComponent.vue` — rather than each page building its own table markup. The page itself just builds a plain JS data structure describing its tabs, columns, and per-cell rendering, and hands it to `<tabsComponent :table_data="...">`.

Referenced from [USER_PAGE.md](../USER_PAGE.md) and [SHOP.md](../SHOP.md) as "the `tabsComponent` pattern" — this is the full contract.

---

## Table of Contents

- [Component Files](#component-files)
- [Top-Level Shape: `table_data`](#top-level-shape-table_data)
- [Column Definitions: `head` / `body` / `perm`](#column-definitions-head--body--perm)
- [Cell Type DSL (`body` row format)](#cell-type-dsl-body-row-format)
- [Row-Level Actions](#row-level-actions)
- [Add Button, Search, Pagination, Bulk Selection](#add-button-search-pagination-bulk-selection)
- [Why Each Page Builds Its Own Tab Definition](#why-each-page-builds-its-own-tab-definition)
- [Minimal Working Example](#minimal-working-example)

---

## Component Files

| File | Role |
|---|---|
| `resources/js/components/user/items/data_table/TabsComponent.vue` | Top-level: tab switcher, search box, add-button, pagination, bulk-selection toolbar. Owns `currentPage`/`selectedItems`/sort state. |
| `resources/js/components/user/items/data_table/assets/TabBodyComponent.vue` | Renders `<tbody>` — one `<tr>` per row, one `<td>` per `body` column, dispatches the cell-type DSL below. **Do not modify** — see [Why](#why-each-page-builds-its-own-tab-definition). |
| `resources/js/components/user/items/data_table/assets/DataComponent.vue` | Child of `TabBodyComponent`, renders the `['data', ...]` cell type specifically (field lookup, nested-field lookup, and the boolean-icon convention). |
| `resources/js/components/user/items/data_table/assets/TabHeaderComponent.vue` | Renders `<thead>` from `tab.head`, wires sort-by-column clicks and the select-all checkbox. |
| `resources/js/components/user/items/data_table/assets/{SearchComponent,PaginationComponent,FilterComponent}.vue` | Search box, pager controls, and an optional filter bar — all generic, driven by the same `table_data`. |

## Top-Level Shape: `table_data`

The `table_data` prop passed to `<tabsComponent>` is an **array of tab objects** — one admin page can show multiple tabs (e.g. `comments_&_reviews_page.vue` has one tab per content type: guidebook comments, shop feedback, route reviews). Built directly in the page's `data()`/methods, pushed to after an axios call resolves — see [Minimal Working Example](#minimal-working-example).

```js
{
  id: 1,                              // unique per tab, used as the radio-button value
  table_name: this.$t('...'),         // tab label
  list_page: 'https://...' | undefined,  // optional "go to public list" link button above the table
  add_action: {                       // optional "+ Add new" button above the table
    action: 'route' | 'url' | 'fun',  // 'route' → router-link, 'url' → <a href>, 'fun'/'function' → emits `link` as an event
    link: 'routeName' | 'https://...' | 'eventName',
    params: { ... },                  // only used for action:'route'
    class: 'btn btn-primary',
    btn_title: '...',                 // optional, defaults to $t('admin.table.add_new')
  },
  tab_data: {
    data: [ { id: 1, ...rowFields }, ... ],   // the actual rows. Each row MUST have a unique `id`.
    tab: { head: [...], body: [...], perm: [...] },  // column definitions, see below
  },
}
```

`row._row_class` (optional, set per-row in the mapped data, not part of `tab.head`/`tab.body`) sets the `<tr>`'s CSS class — used e.g. by the comments moderation table to color a row `table-warning`/`table-danger` for unpublished/admin-hidden rows (`comments_&_reviews_page.vue`).

## Column Definitions: `head` / `body` / `perm`

Three parallel arrays, same length, same column order:

- **`head`**: array of column label strings (already translated via `$t(...)` by the page).
- **`body`**: array of **cell configs** — see the DSL below. One entry per column, applied to every row.
- **`perm`**: array of per-column visibility gates, same index as `head`/`body`:
  - `['no']` — always show this column (no permission check).
  - `[subject, action]` — column only renders (`TabBodyComponent::canShowCell`) if `this.$can(action, subject)` is true — reuses the same [CASL](../AUTH.md#role--permission-system) check as everywhere else in the frontend. Lets one shared tab definition hide, say, a "delete" action column for a user who only has `edit` permission, without the page needing two different tab definitions.

## Cell Type DSL (`body` row format)

Each entry in `body` is an array; `cellConfig[0]` is the type tag. This is the single most important thing to get right when building a new admin table page — get a type tag or the field-path shape wrong and the cell silently renders blank rather than throwing.

| `cellConfig[0]` | Shape | Renders |
|---|---|---|
| `'data'` | `['data', ['field']]` | `row[field]`, plain text |
| `'data'` | `['data', ['field'], 'bool']` | Same lookup, forced into the ✓/✗/⚠ boolean-icon convention below, even if `row[field]` isn't a real JS boolean (works for `0`/`1`, `null`, actual `true`/`false`) |
| `'data'` | `['data', ['item', 'key']]` | Nested lookup: `row[item][key]` (e.g. `['data', [['comment','id']]]` used to read `row.comment.id` in the comments admin table) |
| `'data'` | `['data', ['item', 'key'], 'bool']` | Nested lookup, boolean-icon forced |
| `'data'` | `['data', ['item1'], ['item2']]` | Two **top-level** fields concatenated with a space: `row[item1] row[item2]` |
| `'data'` | `['data', [['item1','k1'],['item2','k2']]]` | Two **nested** fields concatenated: `row[item1][k1] row[item2][k2]` (this is the "double array of pairs" form — easy to confuse with the single-nested form above; the difference is whether the whole pair-list is wrapped in one more array) |
| `'stars'` | `['stars', ['item','key']]` (or `['stars', 'field']`) | 5 `<i class="fa fa-star">` icons, filled up to `getNestedValue(row, path)` |
| `'action_fun_id'` | `['action_fun_id', 'eventName', 'btn btn-...', '<i class="fa fa-eye"></i>']` | A `<button>`; click re-emits `eventName` from `TabsComponent` up to the page, with the row's `id` as payload — the page listens with `@eventName="handler"` on `<tabsComponent>`. This is how "show/edit/hide/delete" row buttons work (see [Row-Level Actions](#row-level-actions)). |
| `'action_router'` | `['action_router', 'routeName', 'btn btn-...', '<i>...</i>']` | A `<router-link :to="{name: routeName, params:{id: row.id}}">` styled as a button |
| `'data_action_id'` | `['data_action_id', ['field'], 'eventName']` | Clickable, underlined text showing `row[field]`; click re-emits `eventName` with the row id (same mechanism as `action_fun_id`, but text instead of a button — used for e.g. "click the article title to open the edit modal") |
| `'data_action_id'` | `['data_action_id', ['field']]` (no trailing action string) | Same text display, but **not clickable** (no action name → `onDataActionClick` no-ops) |
| *(no match)* | any other/malformed config | Falls through to a bare `{{ row[cellConfig[1]] }}` — this is a silent fallback, not an error, so a typo'd type tag doesn't fail loudly |

**Boolean-icon convention** (`'bool'`): green check (`fa-check`, `.fa_check_color`) for truthy/`1`/`true`, red X (`fa-times`, `.fa_times_color`) for falsy/`0`/`false`/`null`, orange exclamation (`fa-exclamation`, `.fa_exclamation_color`) for anything else (e.g. `undefined` from a missing field) — this three-state fallback is why `'bool'` is preferred over relying on a field happening to already be a real boolean: a missing/renamed field shows as an orange "unknown" flag instead of silently rendering as "false".

## Row-Level Actions

`action_fun_id` / `data_action_id` cells don't call a method directly — they emit an event **named after the string you put in the cell config**, which `TabBodyComponent` bubbles up through `TabsComponent` (`action_for_perent_component` → `this.$emit(event[0], event[1])`) to whatever page embeds `<tabsComponent>`. The page wires it up exactly like any other Vue event:

```html
<tabsComponent
  :table_data="data_for_tab"
  @show_comment_modal="on_show_comment"
  @hide_comment="on_admin_hide_comment"
/>
```

```js
["action_fun_id", "show_comment_modal", "btn btn-info btn-sm", '<i class="fa fa-eye"></i>'],
["action_fun_id", "hide_comment", "btn btn-warning btn-sm", '<i class="fa fa-eye-slash"></i>'],
```

Both buttons call `on_show_comment(rowId)` / `on_admin_hide_comment(rowId)` respectively on the page component — the event name is arbitrary, it just has to match between the cell config string and the `@`-listener.

## Add Button, Search, Pagination, Bulk Selection

- **Search**: `SearchComponent` emits `@search`, `TabsComponent` filters `tab_data.data` client-side via `deepSearchValue()` (recursive: strings/numbers/nested objects all searched) — this is an **in-memory filter over whatever rows the page already fetched**, not a server-side search request. For a table with more rows than one page load can reasonably hold, the page itself is responsible for narrowing what it fetches (this system has no server-pagination integration).
- **Pagination**: `PaginationComponent`, driven by `itemsPerPage`/`itemsPerPageOptions` props on `TabsComponent` — also client-side, slicing the already-fetched (and already search-filtered) array.
- **Bulk selection**: pass `:selection_functions="true"` (default) to show row checkboxes + a select-all header checkbox; `false` (as e.g. `comments_&_reviews_page.vue` does) hides them entirely for a page with no bulk actions. Selected row ids are tracked in `TabsComponent`'s `selectedItems` and exposed to the page for wiring a bulk-delete/bulk-publish button separately (not part of this component itself).

## Why Each Page Builds Its Own Tab Definition

There is deliberately **no shared helper** that builds a common `head`/`body`/`perm` triple for, say, "every table that has an id/name/published column" — each admin page's `get_*` data-loading method builds its own tab definition inline, even when two pages' tables look very similar. This is intentional, not an oversight: a shared builder would couple unrelated pages together, so a column tweak on one table risks silently changing another. Keep new admin tables self-contained the same way.

Likewise, **never add a new cell-type tag to `TabBodyComponent.vue`** for a one-off display need — express it with the existing `'data'`/`'bool'`/`'stars'`/`'action_fun_id'`/`'action_router'`/`'data_action_id'` vocabulary above (e.g. a status badge is still just `['data', ['status_field'], 'bool']` or a plain `['data', ['status_field']]` with the raw string, not a new `'badge'` type). The existing vocabulary already covers every real admin table in the codebase; a new type tag is a bigger, more fragile change than it looks, since `TabBodyComponent` is shared by every admin list page at once.

## Minimal Working Example

```js
methods: {
  get_all_things_data() {
    axios.get('get_thing/get_all_things').then(response => {
      this.data_for_tab = [{
        id: 1,
        table_name: this.$t('admin.things.table_title'),
        add_action: { action: 'route', link: 'thingAdd', class: 'btn btn-primary' },
        tab_data: {
          data: response.data,   // [{id, name, published, ...}, ...]
          tab: {
            head: [this.$t('common.id'), this.$t('common.name'), this.$t('admin.common.published'), this.$t('admin.common.actions')],
            body: [
              ['data', ['id']],
              ['data', ['name']],
              ['data', ['published'], 'bool'],
              ['action_router', 'thingEdit', 'btn btn-primary btn-sm', '<i class="fa fa-pen"></i>'],
            ],
            perm: [['no'], ['no'], ['no'], ['thing', 'edit']],   // edit button only shown if $can('edit','thing')
          },
        },
      }]
    })
  }
}
```

```html
<tabsComponent :table_data="data_for_tab" @update="get_all_things_data" />
```

---

[Go back](../../README.md)
