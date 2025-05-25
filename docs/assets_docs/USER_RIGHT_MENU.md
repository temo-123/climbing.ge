<h1 align="center">User menu arguments</h1>

<p>Creating user menu using json code</p>


```bash
export const navbar = {
    admin_all_menu(){
        return [
            {
                title: "Home",
                route: "/",
            },
            {
                title: "Task panel",
                route: "/task_panel",
                permissions: [
                    ['show', 'worker']
                ]
            },
            {
                title: "Favorites",
                name: "favorites",
                routes: [
                    {
                        name: "Favorite products",
                        route: "/favorite_products",
                        permissions: [
                            ['show', 'worker']
                        ]
                    },
                ],
            },
        ]
    }
}
```
