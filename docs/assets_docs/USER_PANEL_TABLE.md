# User Panel Table Documentation

## Overview

The User Panel Table is a Vue.js component system that provides a tabbed data table interface for managing various data entities in the user panel. It consists of the main `TabsComponent.vue` and several sub-components (assets) for enhanced functionality.

## TabsComponent.vue

**Location:** `resources/js/components/user/items/data_table/TabsComponent.vue`

The main component renders a tabbed interface for displaying and managing data tables.

### Features

- **Tabbed Navigation:** Radio button-based tabs for switching between different data tables
- **Search Functionality:** Integrated search component for filtering data
- **Add Actions:** Dynamic buttons for adding new records (route, URL, or function-based)
- **Refresh:** Button to refresh the current table data
- **Filters:** Optional filtering component for advanced data filtering
- **Pagination:** Navigation controls for paginated data
- **Actions:** Row-level actions like edit, delete, view, etc.

### Props

- `table_data`: Array of table configurations (as described below)

### Data Variables

- `tab_num`: Current active tab ID (default 1)

### Events Emitted

- `update`: Triggered when refresh button is clicked
- `pagechanged`: Emitted for pagination changes with page number
- Various filter and action events based on table configuration

### Methods

- `update()`: Emits update event to parent
- `filter_data(emit_fun)`: Emits filter event without ID
- `filter_data_with_id(emit_fun)`: Emits filter event with ID
- `action_for_perent_component_with_option(event)`: Emits action event with option
- `action_for_perent_component(event)`: Emits general action event
- `privies_page_pagination()`: Handles previous page navigation
- `nomber_page_pagination(page_id)`: Handles page number navigation
- `next_page_pagination()`: Handles next page navigation

## Assets (Sub-components)

The TabsComponent uses several sub-components located in the `assets/` folder:

### FilterComponent.vue

Provides filtering options for the table data.

- **Props:** `filtr_data_prop` (action_data object containing filter configuration)
- **Data Variables:**
  - `action_data`: Stores the filter data prop
  - `filter_id`: Selected filter ID (default 0 for "All")
- **Events Emitted:**
  - `send_filter_to_tab_with_id`: Emitted when filter with ID is selected, passes [function_name, filter_id]
  - `send_filter_to_tab`: Emitted for general filter actions, passes [function_name]
- **Methods:**
  - `send_filter_to_tab_with_id(option_fun)`: Emits filter event with ID
  - `send_filter_to_tab(option_fun)`: Emits general filter event

### PaginationComponent.vue

Handles pagination controls for navigating through data pages.

- **Props:** `pagination_data_prop` (pagination configuration)
- **Events Emitted:**
  - `next_page_pagination`: Triggered on next page button click
  - `privies_page_pagination`: Triggered on previous page button click (note: typo in code)
  - `nomber_page_pagination`: Triggered on page number click, passes page_id
- **Methods:**
  - `next_page_pagination()`: Emits next page event
  - `privies_page_pagination()`: Emits previous page event
  - `nomber_page_pagination(page_id)`: Emits page number event

### SearchComponent.vue

Provides search functionality to filter table data.

- **Props:** `body_data_prop` (body data configuration)
- **Data Variables:** None active (commented out)
- **Methods:**
  - `filteredProL()`: Filters products based on search input (logic for filtering arrays)

### TabHeaderComponent.vue

Renders the table header based on the provided head data.

- **Props:** `head_data_prop` (tab data object)
- **Data Variables:**
  - `tab_data`: Stores the header data prop
- **Features:**
  - Renders checkbox for select all
  - Conditionally shows columns based on permissions using `$can()` method
  - Checks permissions against `tab_data.tab.perm[h_key]`

### TabBodyComponent.vue

Renders the table body with data rows and action buttons.

- **Props:** `body_data_prop` (tab data object)
- **Data Variables:**
  - `tab_data`: Stores the body data prop
  - `danger_color`: CSS class for row styling (currently empty)
- **Events Emitted:**
  - `action_for_perent_component_with_option`: Emitted for actions with options, passes [function_name, id]
  - `action_for_perent_component`: Emitted for general actions, passes [function_name]
- **Methods:**
  - `send_action_to_tab_with_option(emit_fun, sending_id)`: Emits action with option
  - `send_action_to_tab(emit_fun)`: Emits general action
  - `setPages()`: Calculates number of pages for pagination
  - `paginate(posts)`: Returns paginated subset of data
- **Supported Action Types:**
  - `data`: Displays data value
  - `data_action_id`: Clickable data that triggers action with ID
  - `data_action`: Clickable data that triggers action
  - `action_fun`: Button that triggers function
  - `stars`: Displays star rating component
  - `action_url`: Link to URL
  - `action_router`: Vue router link
  - `action_fun_id`: Button that triggers function with ID

### DataComponent.vue

Handles rendering of individual data items in table cells.

- **Props:**
  - `data_item_prop`: Configuration array for the data item
  - `data_prop`: The actual data object
- **Features:**
  - Supports nested object access (e.g., `data['user']['id']`)
  - Boolean rendering with icons (check, times, exclamation)
  - Multiple data display formats
  - Special handling for 'bool' and 'bool_2' types

## Data Structure

For register need insert your information in registration page. After registration API send confirming message.

After confirming API create user_role and user_notificaions tabs in DB. All notifications defolt is activw. Beore thet us service (without role) is inpaseble.

```bash
'id': 1,
'table_name': 'Tab Name',
'list_page': https://climbing.ge/outdoor/,
```

| Options                   |  Description                   |
|:-:                        |  :---:                         |
| ```bash id ```            |  Table ID                      |
| ```bash table_name ```    |  Table name                    |
| ```bash list_page ```     |  Table data lisit (optional)   |

### Add Button action

Add argument for "add_action" object argument.

```bash
'add_action': {
    'action': 'route',
    'link': 'articleAdd',
    'class': 'btn btn-primary'
},
```

| Options               |   Description                 |      Arguments                                                   |
|:-:                    |   :---:                       |      :---:                                                       |
| ```bash action ```    |   Functional action argument  |  ```bash route``` vue route operator. ```bash fun``` / ```bash function``` function operator  |
| ```bash link ```      |   Function or route name      |  .                                                               |
| ```bash class ```     |   Button styles               |  Optional                                                        |

### "tab_data" Array

The table is created by "tab data" operators. Accept 2 Parameters. (data, tab).

```bash
'tab_data': {
    'data': response.data,
    'tab': []
```

| Options              |   Description                     |
|:-:                   |   :---:                           |
| ```bash data ```     |   Accept data array for table    |
| ```bash tab ```      |   Accept header parameters       |

### "tab" Array

The table is created by "tab" operators. Accept 3 Parameters. (head, body, perm). It is important that the parameters are of the same quantity.

```bash
'tab': {
    'head': []
    'body': []
    'perm': []
}
```

| Options              |   Description                     |
|:-:                   |   :---:                           |
| ```bash data ```     |   Accept data array for table    |
| ```bash head ```     |   Accept header parameters       |
| ```bash body ```     |   Accept body parameters         |
| ```bash perm ```     |   Accept permissions parameters  |

### Tab Header

'head' accept tab header names without arduments.

```bash
'head': [
    'ID',
    'Title',
    'Public',
],
```

### Tab Body

Arguments for table body.

```bash
'body': [
    ['data', ['id']],
    ['data_action_id', ['url_title'], 'quick_wiev_action'],
    ['data', ['published'], 'bool'],
    ['action_router', 'articleEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
    ['action_fun_id', 'del_article', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
],
```

| Options                         |  Description           |   Parameters           |       #3               |            #4                         |
|:-:                              |   :---:        |      :---:     |      :---:             |          :---:                        |
| ```bash data ```                |  fun_name      |  button class  |  title or html object  |  ['user', 'id']                       |
| ```bash data_action_id ```      |  fun_name      |  button class  |  title or html object  |  ['user', 'id']                       |
| ```bash data_action ```         |  fun_name      |  button class  |  title or html object  |  ['user', 'id']                       |
| ```bash action_fun ```          |  fun_name      |  button class  |  title or html object  |  ['user', 'id']                       |
| ```bash stars ```               |  fun_name      |  button class  |  title or html object  |  ['user', 'id']                       |
| ```bash action_url ```          |  fun_name      |  button class  |  title or html object  |  ['user', 'id']                       |
| ```bash action_router ```       |  fun_name      |  button class  |  title or html object  |  ['user', 'id']                       |
| ```bash action_fun_id ```       |  fun_name      |  button class  |  title or html object  |  ['user', 'id']                       |
| ```bash action_fun_id ```       |  fun_name      |  button class  |  title or html object  |  [['user', 'id'], ['product', 'id']]  |
| ```bash action_fun_id ```       |  fun_name      |  button class  |  title or html object  |  [['user', 'id'], ['product', 'id']]  |

### Tab Permissions

"perm" Arguments Permissions accept arguments for table columns. If permission not corresponds to user rights it delete all lolumb. metmision argument work in a column turns. Permissions work with "casle abylyti" plagin

```bash
'perm': [
    ['no'],
    ['article', 'del'],
]
```

| Options                                   |   Description                                 |
|:-:                                        |   :---:                                       |
| ```bash ['no'] ```                        |   If option is "no" column showeble anyway    |
| ```bash ['entities', 'action'] ```        |   Permissions operators Accept 2 params entities and action. If permission not corresponds to user rights it delete all lolumb.  |

### Total exemple

```bash
'id': 1,
'table_name': 'Tab Name',
'list_page': https://climbing.ge/outdoor/,
'add_action': {
    'action': 'route',
    'link': 'articleAdd',
    'class': 'btn btn-primary'
},
'tab_data': {
    'data': response.data,
    'tab': {
        'head': [
            'ID',
            'Title',
            'Public',
            'Edit',
            'Delite',
        ],
        'body': [
            ['data', ['id']],
            ['data_action_id', ['url_title'], 'quick_wiev_action'],
            ['data', ['published'], 'bool'],
            ['action_router', 'articleEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
            ['action_fun_id', 'del_article', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
        ],
        'perm': [
            ['no'],
            ['no'],
            ['no'],
            ['article', 'edit'],
            ['article', 'del'],
        ]
    }
},
