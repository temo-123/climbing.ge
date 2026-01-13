<template>
    <span class="left_navbar">
        <input type="checkbox" v-model="menu_position" @change="update_menu_position" id="check">
        <!-- <label for="check">
            <span id="open_menu"> -> </span>
            <span id="close_menu">{{ "<-" }}</span>
        </label> -->
        <div class="sidebar left_sidebar" :class="{ animate: animate_enabled }">
            <header>Menu</header>

            <ul v-for="menu_item in menu_items" :key="menu_item.id" style="padding-left: 0px;">

                <li v-if="menu_item.routes && haveMenuBlockPermission(menu_item)" :class="{menu_item, active: isAnySubActive(menu_item.routes) }">
                  <a href="#" @click="toggle_dropdown(menu_item.name)" class="dropdown-toggle" >{{menu_item.title}}</a>
                  <ul style="background-color: #04354b;" v-show="is_dropdown_open(menu_item.name)" :class="menu_item.name">
                    <span v-for="menu_but in menu_item.routes" :key="menu_but.id">

                      <li v-if="menu_but.hasOwnProperty('permissions') && haveMenuButPermission(menu_but.permissions)" :class="{ active: isActive(menu_but.route) }">
                        <router-link :to="{path: menu_but.route}">
                          {{menu_but.name}}
                        </router-link>
                      </li>

                    </span>
                  </ul>
                </li>

                <li v-else-if="menu_item.route && menu_item.hasOwnProperty('permissions') && haveMenuButPermission(menu_item.permissions)" :class="{ active: isActive(menu_item.route) }">
                  <router-link :to="{path: menu_item.route}" >{{menu_item.title}}</router-link>
                </li>

                <li v-else-if="menu_item.route && !menu_item.hasOwnProperty('permissions')" :class="{ active: isActive(menu_item.route) }">
                  <router-link :to="{path: menu_item.route}" >{{menu_item.title}}</router-link>
                </li>
            </ul>
<!-- 
            <ul style="padding-left: 0px;" @click="logout()">
                <li><a>{{ $t('user.menu.logout') }}</a></li>
            </ul> -->

        </div>
    </span>
</template>

<script>
    import { navbar } from '../../../../mixins/navbar_pages_mixin.js'
    export default {
        mixins: [
          navbar
        ],
	    name: 'leftMenu',
        data(){
            return {
                width: 0,
                menu: true,
                menu_but: true,

                menu_array: {},
                menu_position: false,
                animate_enabled: false,

                menu_items: navbar.admin_all_menu(),

                item_1: false,
                // Store open dropdowns state
                open_dropdowns: JSON.parse(localStorage.getItem('left_menu_open_dropdowns') || '{}')
            }
        },
        mounted() {
            this.window_size()
            // Load open dropdowns state from localStorage
            this.open_dropdowns = JSON.parse(localStorage.getItem('left_menu_open_dropdowns') || '{}');
            
            // Listen for menu toggle events from NavbarComponent
            this.$root.$on('menu-toggle', () => {
                this.animate_enabled = true;
                // Use requestAnimationFrame to ensure CSS transition is applied before position change
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        this.animate_enabled = false;
                    }, 500);
                });
            });

            if (window.innerWidth > 993) {
              if(!localStorage.getItem('left_menu_position')){
                localStorage.setItem('left_menu_position', true);
                this.menu_position = true

                document.querySelector('body').style.marginLeft = '22em';
                document.querySelector('.admin_page_header_navbar').style.marginLeft = '22em';
              }
              else{
                this.menu_position = (localStorage.getItem('left_menu_position') === 'true');
                if(this.menu_position){
                  document.querySelector('body').style.marginLeft = '22em';
                  document.querySelector('.admin_page_header_navbar').style.marginLeft = '22em';
                }
                else{
                  document.querySelector('body').style.marginLeft = '0';
                  document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
                }
              }
            } else {
              document.querySelector('body').style.marginLeft = '0';
              document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
            }

        },
        unmounted() {
            this.window_size()
            this.$root.$off('menu-toggle');
        },
        methods: {
            haveMenuButPermission(permissions){
              if(permissions.length){
                let perms = permissions
                for (let i = 0; i < perms.length; i++) {
                  if(this.$can(perms[i][0], perms[i][1])){
                    return true
                  }
                  else{
                    return false
                  }
                }
              }
              else{
                return true
              }
            },
            haveMenuBlockPermission(menu_section){
              let perm_num = 0;

              for (let j = 0; j < menu_section.routes.length; j++) {
                if (menu_section.routes[j].hasOwnProperty('permissions') && menu_section.routes[j].permissions.length) {
                  for (let i = 0; i < menu_section.routes[j].permissions.length; i++) {
                    if(this.$can(menu_section.routes[j].permissions[i][0], menu_section.routes[j].permissions[i][1])){
                      perm_num++
                    }
                  }
                }
                else if(!menu_section.routes[j].hasOwnProperty('permissions') || !menu_section.routes[j].permissions.length){
                  perm_num++
                }
              }

              if(perm_num > 0){
                return true
              }
              else{
                return false
              }
            },
            show_item(item_class){
              if (document.querySelector('.'+item_class).style.display == 'none') {
                document.querySelector('.'+item_class).style.display = '';
              }
              else {
                document.querySelector('.'+item_class).style.display = 'none'
              }
            },
            logout(){
                axios
                .post(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/logout')
                .then(()=>{
                    localStorage.removeItem('x_xsrf_token');
                    this.$router.push({ name: "login" });
                })
                
            },
            window_size: function(){
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

            // logout(){
            //     axios
            //     .post(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/logout')
            //     .then(()=>{
            //         localStorage.removeItem('x_xsrf_token');
            //         this.$router.push({ name: "login" });
            //     })
                
            // },

            update_menu_position(){
              // Enable animation before position change
              this.animate_enabled = true;
              
              // Use requestAnimationFrame to ensure CSS transition is applied before next paint
              requestAnimationFrame(() => {
                if(!this.menu_position){
                  localStorage.setItem('left_menu_position', false)
                  this.menu_position = false
                  document.querySelector('body').style.marginLeft = '0';
                  document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
                }
                else{
                  localStorage.setItem('left_menu_position', true)
                  this.menu_position = true
                  document.querySelector('body').style.marginLeft = '22em';
                  document.querySelector('.admin_page_header_navbar').style.marginLeft = '22em';
                }
                
                // Reset animation after transition completes
                setTimeout(() => {
                  this.animate_enabled = false;
                }, 500);
              });
            },
            open_menu: function(){
                if (this.menu) {
                    this.menu = false
                }
                else{
                    this.menu = true
                }
            },
            isActive(route) {
                return this.$route.path === route;
            },
            isAnySubActive(routes) {
                return routes.some(sub => this.isActive(sub.route));
            },
            toggle_dropdown(menu_name) {
                // If clicking on an already open dropdown, close it
                if (this.open_dropdowns[menu_name] === true) {
                    this.$set(this.open_dropdowns, menu_name, false);
                } else {
                    // Close all dropdowns first
                    for (let key in this.open_dropdowns) {
                        this.$set(this.open_dropdowns, key, false);
                    }
                    // Open only the clicked dropdown
                    this.$set(this.open_dropdowns, menu_name, true);
                }
                // Persist to localStorage
                localStorage.setItem('left_menu_open_dropdowns', JSON.stringify(this.open_dropdowns));
            },
            is_dropdown_open(menu_name) {
                // Check if dropdown should be open
                return this.open_dropdowns[menu_name] === true;
            }
        },
        watch: {
            // Watch for route changes to maintain dropdown state if needed
            '$route.path'() {
                // Optionally close dropdowns on navigation if desired
                // this.close_all_dropdowns();
            }
        }
    }
</script>

<style scoped>
.sidebar {
  position: fixed;
  top: 0;
  left: -22em;
  width: 22em;
  height: 100%;
  background: #042331;
}
.sidebar.animate {
  transition: all .5s ease;
}
.left_sidebar {
  overflow:auto;
}
.sidebar header {
  font-size: 22px;
  color: white;
  line-height: 54.5px;
  text-align: center;
  background: #063146;
  user-select: none;
}
.sidebar ul a{
  display: block;
  height: 100%;
  width: 100%;
  line-height: 65px;
  font-size: 20px;
  color: white;
  padding-left: 40px;
  box-sizing: border-box;
  border-bottom: 1px solid black;
  /* border-top: 1px solid rgba(255,255,255,.1); */
  transition: .4s;
}
.sidebar ul a i{
  margin-right: 16px;
}
#check{
  display: none;
}
label #open_menu,label #close_menu{
  position: absolute;
  cursor: pointer;
}
#check:checked ~ .sidebar{
  left: 0;
}
#check:checked ~ label #open_menu{
  left: 22em;
  opacity: 0;
  pointer-events: none;
}
#check:checked ~ label #close_menu{
  left: 195px;
}

@media (max-width: 993px) {
  .left_navbar{
    margin-left: 0;
  }
  .admin_page_header_navbar{
    margin-left: 0;
  }
  body{
    margin-left: 0;
  }
}

.active {
  background-color: #063146 !important;
  border-left: 4px solid #4CAF50;
  transition: all 0.3s ease;
}

.sidebar ul li:hover {
  background-color: #063146;
  /* border-left: 4px solid #ffffff; */
  transition: all 0.8s ease;
  /* margin-left: 4px; */
}
.menu_item li:hover {
  background-color: #04354b;
  border-left: 4px solid #ffffff;
  transition: all 0.6s ease;
}

.left_sidebar ul {
  /* padding: 0; */
  margin: 0;
}
</style>
