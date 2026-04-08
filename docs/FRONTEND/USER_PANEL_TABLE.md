# User Panel Table Documentation

## Overview

Tabbed data table for user panel: `TabsComponent.vue` + assets.

**Location:** `resources/js/components/user/items/data_table/TabsComponent.vue`

### Features

- Tabs, search, add, refresh, filters, pagination, actions

### Sub-components

- FilterComponent.vue, PaginationComponent.vue, SearchComponent.vue, TabHeaderComponent.vue, TabBodyComponent.vue, DataComponent.vue

### Data Structure

Table config:

```javascript
{
  id: 1,
  table_name: 'Tab Name',
  list_page: 'https://climbing.ge/outdoor/',
  add_action: { action: 'route', link: 'articleAdd', class: 'btn btn-primary' },
  tab_data: {
    data: response.data,
    tab: {
      head: ['ID', 'Title', 'Public'],
      body: [
        ['data', ['id']],
        ['data_action_id', ['url_title'], 'quick_view_action'],
        ['data', ['published'], 'bool']
      ],
      perm: [['no'], ['no'], ['no']]
    }
  }
}
```

Action types: `data`, `data_action_id`, `action_router`, `stars`, etc.

Permissions with casl-vue.

See original for full details.

<hr>

[Go back](../README.md)

