import { createInertiaApp } from '@inertiajs/vue2'
import createServer from '@inertiajs/vue2/server'
import Vue from 'vue'
import { createRenderer } from 'vue-server-renderer'

createServer(page =>
  createInertiaApp({
    page,
    render: createRenderer().renderToString,
    resolve: name => {
      const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
      return pages[`./Pages/${name}.vue`]
    },
    setup({ App, props, plugin }) {
      Vue.use(plugin)
      return new Vue({
        render: h => h(App, props),
      })
    },
  }),
)

// https://inertiajs.com/server-side-rendering
// https://www.google.com/search?client=ubuntu-sn&sca_esv=4d7efd29b5dccb68&sca_upv=1&channel=fs&sxsrf=ADLYWIJUUxNJcFx4cOuZ1Qac8xatlAFKdw:1714826001682&q=vue+2+laravel+server+side+rendering&tbm=vid&source=lnms&prmd=visnbmtz&sa=X&ved=2ahUKEwip57nVgPSFAxVSVfEDHejgBwgQ0pQJegQICxAB&biw=1850&bih=968&dpr=2