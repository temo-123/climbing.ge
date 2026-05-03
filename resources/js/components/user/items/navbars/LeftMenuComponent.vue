<template>
    <span class="left_navbar">

        <input type="checkbox" v-model="menu_position" @change="update_menu_position" id="check" class="menu-checkbox">
        
        <div class="sidebar left_sidebar" :class="{ 'open': menu_position, 'animate': animate_enabled }" style="padding-bottom: 15%;">
            <header>Menu</header>

            <ul v-for="(item, index) in menu_items" :key="(item?.id || item?.title || index) + '-' + permissionsKey" style="padding-left: 0px; /*padding-bottom: 35%;*/" v-if="menu_items && menu_items.length">
                <li v-if="item && item.routes && haveMenuBlockPermission(item)" :class="['menu_item', { active: isAnySubActive(item.routes) }]">
                  <a href="javascript:void(0)" @click.prevent="toggle_dropdown(item.title)" class="dropdown-toggle">
                    <i class="fas fa-chevron-right dropdown-icon" :class="{ 'rotated': is_dropdown_open(item.title) }"></i>
                    {{item.title}}
                  </a>
                  <ul style="background-color: #04354b;" v-show="is_dropdown_open(item.title)" :class="item.title">
                    <template v-for="(menu_but, menuButIndex) in item.routes || []" :key="menu_but?.name + (item.id || index)">
                      <li v-if="menu_but && (menu_but.hasOwnProperty('permissions') ? haveMenuButPermission(menu_but.permissions) : true)" :class="{ active: isActive(menu_but.route) }">
                        <router-link :to="{path: menu_but.route}">
                          {{menu_but.name}}
                        </router-link>
                      </li>
                    </template>
                  </ul>
                </li>

                <li v-else-if="item && item.route" :class="{ active: isActive(item.route) }">
                  <router-link :to="{path: item.route}">{{item.title}}</router-link>
                </li>
            </ul>

            <div v-else class="menu-loading">Loading menu...</div>

        </div>
    </span>
</template>

<script>
import navbar_pages_mixin from '../../../../mixins/navbar_pages_mixin.js'
    export default {
        mixins: [navbar_pages_mixin],
        name: 'leftMenu',
        data(){
            return {
                width: 0,
                menu_position: false,
                animate_enabled: false,
                open_dropdowns: JSON.parse(localStorage.getItem('left_menu_open_dropdowns') || '{}'),
                permissionsKey: 0,
            }
        },
        computed: {
            menu_items() {
                // permissionsKey dependency makes this recompute when permissions change
                return this.permissionsKey >= 0 ? (this.admin_all_menu() || []) : [];
            },
            isAuthenticated() {
                return !!localStorage.getItem('user_permissions');
            }
        },
        mounted() {
            this.window_size()
            this.open_dropdowns = JSON.parse(localStorage.getItem('left_menu_open_dropdowns') || '{}');
            this.syncMenuPosition();

            this.$bus.$on('permissions-loaded', (permissions) => {
                if (this.$ability) this.$ability.update(permissions)
                this.permissionsKey++;
            })
            this.$bus.$on('logged-in', () => {
                axios.get('get_user/get_auth_user_permissions')
                    .then(response => {
                        if (this.$ability) this.$ability.update(response.data)
                        localStorage.setItem('user_permissions', JSON.stringify(response.data))
                        this.permissionsKey++;
                    })
                    .catch(() => {})
            });

            this.$bus.$on('menu-toggle', () => {
                this.animate_enabled = true;
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        this.animate_enabled = false;
                    }, 500);
                });
            });
        },

        beforeUnmount() {
            this.$bus.$off('permissions-loaded');
            this.$bus.$off('logged-in');
            this.$bus.$off('menu-toggle');
        },

        watch: {
            $route() {
                this.$nextTick(() => this.apply_sidebar_margin(this.menu_position));
            }
        },

        methods: {
            haveMenuButPermission(permissions) {
              if (!permissions || !permissions.length) return true;
              for (let i = 0; i < permissions.length; i++) {
                if (this.$can(permissions[i][0], permissions[i][1])) {
                  return true;
                }
              }
              return false;
            },
            
            haveMenuBlockPermission(menu_section){
              if (!menu_section.routes || !Array.isArray(menu_section.routes)) return false;
              if (!this.$ability) return true;
              
              let perm_num = 0;
              for (let j = 0; j < menu_section.routes.length; j++) {
                const routeItem = menu_section.routes[j];
                if (!routeItem) continue;
                
                if (routeItem.hasOwnProperty('permissions') && routeItem.permissions && routeItem.permissions.length) {
                  for (let i = 0; i < routeItem.permissions.length; i++) {
                    if(this.$can(routeItem.permissions[i][0], routeItem.permissions[i][1])){
                      perm_num++
                      break;
                    }
                  }
                } else {
                  perm_num++;
                }
              }
              return perm_num > 0;
            },

            window_size() {
                this.width = innerWidth;
                if (this.width > 575) {
                    this.menu = true
                    this.menu_but = true
                }
                else{
                    this.menu = false
                    this.menu_but = false
                }
            },

            syncMenuPosition() {
                if (window.innerWidth > 993) {
                    if (!localStorage.getItem('left_menu_position')) {
                        localStorage.setItem('left_menu_position', 'true');
                        this.menu_position = true;
                    } else {
                        this.menu_position = (localStorage.getItem('left_menu_position') === 'true');
                    }
                } else {
                    this.menu_position = false;
                }
                this.apply_sidebar_margin(this.menu_position);
            },

            apply_sidebar_margin(open) {
                if (open && window.innerWidth > 993) {
                    document.body.classList.add('sidebar-open');
                } else {
                    document.body.classList.remove('sidebar-open');
                }
            },

            update_menu_position() {
                this.animate_enabled = true;
                localStorage.setItem('left_menu_position', this.menu_position);
                this.apply_sidebar_margin(this.menu_position);
                setTimeout(() => {
                    this.animate_enabled = false;
                }, 500);
            },
            
            isActive(route) {
                return this.$route.path === route;
            },

            isAnySubActive(routes) {
                return routes.some(sub => this.isActive(sub.route));
            },

            toggle_dropdown(menu_name) {
                if (this.open_dropdowns[menu_name] === true) {
                    this.open_dropdowns[menu_name] = false;
                } else {
                    for (let key in this.open_dropdowns) {
                        this.open_dropdowns[key] = false;
                    }
                    this.open_dropdowns[menu_name] = true;
                }
                localStorage.setItem('left_menu_open_dropdowns', JSON.stringify(this.open_dropdowns));
            },

            is_dropdown_open(menu_name) {
                return this.open_dropdowns[menu_name] === true;
            },
            
        },
    }
</script>

<style scoped>
.sidebar {
  position: fixed;
  top: 0;
  left: -22em;
  width: 22em;
  height: 100%;
  z-index: 1001;
  background: #042331;
  transition: left 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.sidebar.animate {
  transition: left 0.5s ease;
}
.sidebar.open {
  left: 0;
}
.left_sidebar {
  overflow: auto;
}
.sidebar header {
  font-size: 22px;
  color: white;
  line-height: 54.5px;
  text-align: center;
  background: #063146;
  user-select: none;
}
.sidebar ul a {
  display: block;
  height: 100%;
  width: 100%;
  line-height: 65px;
  font-size: 20px;
  color: white;
  padding-left: 40px;
  box-sizing: border-box;
  border-bottom: 1px solid black;
  transition: .4s;
}
.sidebar ul a i {
  margin-right: 16px;
}
.menu-checkbox {
  position: fixed;
  opacity: 0;
  z-index: -1;
}
.dropdown-icon {
  transition: transform 0.3s ease;
  margin-right: 10px;
}
.dropdown-icon.rotated {
  transform: rotate(90deg);
}
.active {
  background-color: #063146 !important;
  border-left: 4px solid #4CAF50;
  transition: all 0.3s ease;
}
.sidebar ul li:hover {
  background-color: #063146;
  transition: all 0.8s ease;
}
.menu_item li:hover {
  background-color: #04354b;
  border-left: 4px solid #ffffff;
  transition: all 0.6s ease;
}
.left_sidebar ul {
  margin: 0;
}
</style>
