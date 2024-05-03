<template>
    <div class="row">
        <!-- <div class="col-sm-3"> -->
            <left-menu />
        <!-- </div> -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <!-- <galleryTab /> -->
                    <!-- followers -->
                    <tabsComponent 
                        :table_data="this.data_for_tab"
                        @update-data="get_site_followers"
                        @filtr="filtr"
                    />
                </div>
            </div>
         </div>
    </div>
</template>

<script>
    import breadcrumb from '../items/BreadcrumbComponent.vue'
    import tabsComponent  from '../items/data_tabs/DataTab/TabsComponent'

    // import galleryTab from '../items/data_tabs/GalleryTabComponent.vue';
    export default {
        data(){
            return {
                users: [],
                data_for_tab: []
            }
        },
        components: {
            // galleryTab,
            tabsComponent,
            breadcrumb
        },
        mounted() {
            this.get_site_followers()
        },
    //     watch: {
    //         '$route' (to, from) {
    //             this.categories = [],
    //             this.get_images_categories()
    //             window.scrollTo(0,0)
    //         }
    //     },
        methods: {
            filtr(event){
                // this.get_site_followers(event)
                this.get_site_followers()
            },
            get_site_followers(){
                this.data_for_tab = []
                axios
                .get("/follow/following_users_list/")
                .then(response => {
                    this.users = response.data
                    this.data_for_tab.push({'id': 1,
                                            'data': this.users, 
                                            'table_name': 'Site Followers', 
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>
