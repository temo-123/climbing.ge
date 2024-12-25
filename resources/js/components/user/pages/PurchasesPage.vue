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
                    <tabsComponent 
                        :table_data="this.data_for_tab"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import tabsComponent  from '../items/data_table/TabsComponent.vue'
import breadcrumb from '../items/BreadcrumbComponent.vue'
export default {
    components: {
        breadcrumb,
        tabsComponent ,
        // leftMenu,
    },
    data(){
        return {
            data_for_tab: [],
        }
    },
    mounted() {
        this.get_purchules()
    },
    watch: {
        '$route' (to, from) {
            this.data_for_tab = [],
            this.get_purchules()
            window.scrollTo(0,0)
        }
    },
    methods: {
        get_purchules(){
            axios
            .get("get_user_purchules/")
            .then(response => {
                this.data_for_tab.push({'id': 1,
                                        'data': response.data, 
                                        'table_name': 'My purchules', 
                                        'table_category': '', 
                                    });
            })
            .catch(
                error => console.log(error)
            );
        },
    }
}
</script>