<h1 align="center">User panel table</h1>

<p>For register need insert your information in registration page. After registration API send confirming message.</p>

<p>After confirming API create user_role and user_notificaions tabs in DB. All notifications defolt is activw. Beore thet us service (without role) is inpaseble.</p>

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

<h2 align="center">Add Button action</h2>

<p>Add argument for "add_action" object argument.</p>

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


<h2 align="center">"tab_data" Array</h2>

<p>The table is created by "tab data" operators. Accept 2 Parameters. (data, tab).</p>

```bash
'tab_data': {
    'data': response.data, 
    'tab': []
```

| Options              |   Description                     |
|:-:                   |   :---:                           |
| ```bash data ```     |   Accept data array for table    |
| ```bash tab ```      |   Accept header parameters       |

<h2 align="center">"tab" Array</h2>

<p>The table is created by "tab" operators. Accept 3 Parameters. (head, body, perm). It is important that the parameters are of the same quantity.</p>

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

<h2 align="center">Tab Header</h2>

<p>'head' accept tab header names without arduments.</p>

```bash
'head': [
    'ID',
    'Title',
    'Public',
],
```

<h2 align="center">Tab Body</h2>

<p>Arguments for table body.</p>

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

<h2 align="center">Tab Permissions</h2>

<p>"perm" Arguments Permissions accept arguments for table columns. If permission not corresponds to user rights it delete all lolumb. metmision argument work in a column turns. Permissions work with "casle abylyti" plagin</p>

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

<h2 align="center">Total exemple</h2>

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
```