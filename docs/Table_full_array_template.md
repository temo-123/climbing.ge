```bash
    {  
        'id': 1,
        'table_name': 'name of tab', 
        'list_page': 'go to items list page',
        'add_action': {
            'action': 'route',
            'link': 'articleAdd', 
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
                    ['data', 'id'],
                    ['data', 'url_title'],
                    ['data', 'published'],
                    ['action_router', 'articleEdit', 'btn btn-primary', 'Edit'],
                    ['action_fun', 'del_article', 'btn btn-danger', 'Del'],
                    ['action_fun_id', 'del_article', 'btn btn-danger', 'Del'],
                ],
                'perm': [
                    ['no'],
                    ['no'],
                    ['no'],
                    ['aricle', 'edit'],
                    ['aricle', 'del'],
                ]
            }
        },
        'filter_data': {
            'title': 'Filter by regions',
            'data': this.regions,
            'action_fun': 'make function without ID'
            'action_fun_id': 'return ID for function'
        }
    };
``` 