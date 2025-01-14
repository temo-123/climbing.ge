'id': 1,
    'table_name': 'Tab name', 
    'list_page': 'items list URL',
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
                ['data_action', ['url_title'], 'quick_wiev_action'],
                ['data', ['published']],
                ['data', ['name'], ['surname']],
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