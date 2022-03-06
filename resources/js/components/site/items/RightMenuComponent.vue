<template>
    <div class="col-sm-3 col-sm-offset-1 blog-sidebar display-none-720px">

        <!-- <div class="sidenav">
            <a href="#about">About</a>
            <a href="#services">Services</a>
            <a href="#clients">Clients</a>
            <a href="#contact">Contact</a>
        </div> -->

        <nav class="navbar fading-side-menu">
            <ul class="list-unstyled">
                <li><h4>@lang('site.nav menu')</h4></li>

                <li>
                    <a href="#gallery">
                        <span class="fa fa-angle-double-right text-primary"></span>@lang('site.gallery')
                    </a>
                </li>
            
                <li>
                    <a href="#comments">
                        <span class="fa fa-angle-double-right text-primary"></span>@lang('site.comments')
                    </a>
                </li>
            
                <li>
                    <a href="#other">
                        <span class="fa fa-angle-double-right text-primary"></span>@lang('site.other')
                    </a>
                </li>
            </ul>
        </nav>
      
        <!-- <div class="container clearfix">
            <vue-sticky-sidebar class="sidebar" containerSelector=".container" innerWrapperSelector='.sidebar__inner'>
            <p>This is sticky column</p>
            </vue-sticky-sidebar>
            <div id="content">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tempus id leo et aliquam. Proin consectetur ligula vel neque cursus laoreet. Nullam dignissim, augue at consectetur pellentesque, metus ipsum interdum sapien, quis ornare quam enim vel ipsum. dolor.</p>
            </div>
        </div> -->

        <!--sticky container (optional) -->
        <div class="navbar fading-side-menu" data-v-sticky-container>
            <!-- the actual sticky element -->
            <div class="list-unstyled" v-sticky>
                <!-- where plugin applies transforms (optional) -->
                <div class="your-sidebar-inner-class" data-v-sticky-inner></div>
            </div>
        </div>
    </div>

</template>

<script>
// import vueStickySidebar from "vue-sticky-sidebar"; //https://github.com/blixhavn/sticky-sidebar-v2
// import VueStickyDirective from '@renatodeleao/vue-sticky-directive' //https://www.npmjs.com/package/vue-sticky-sidebar?activeTab=readme
//https://codepen.io/alarik/pen/OGXdLz
 
export default {
    // name: "your-component-name",
    directives: {
        // "sticky": VueStickyDirective
    },
    name: "ServeDev",
    components: {
        // "vue-sticky-sidebar": vueStickySidebar
    }
};
</script> 
