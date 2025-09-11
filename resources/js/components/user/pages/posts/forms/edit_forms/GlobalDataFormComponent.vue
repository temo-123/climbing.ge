<template>
    <div class="colm-md-12">
        <div class="row">
            <div class="col-md-12" >
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">Edit article global information</h2>
                        <p class="lead">Edit article global information.</p>
                    </div>
                </div>
                <form @submit.prevent="edit_article"  style="margin-top: 5%;">

                    <published_item 
                        :published_prop = data.published
                        @item_data="data.published = $event" 
                    />


                </form>
            </div>
        </div>
    </div>
</template>
<script>
    // import { SlickList, SlickItem } from 'vue-slicksort';
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import published_item from '../../../../items/form/parts/PublishedValueComponent.vue'

    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
            published_item
        },
        props: [
            'global_data_prop',
            'category_prop',

            'title_prop',
            'description_prop',

            // 'region_id_prop',
            // 'mount_id_prop'
        ],
        data(){
            return {
                // editorConfig: {
                //     // toolbar: [ [ 'Bold' ] ]
                // },
                data: [],

                region_id: 'select_region',
                mount_id: 'select_mount',

                category: this.category_prop,

                error: [],

                regions: [],
                mount_masive: [],
            }
        },
        watch: {
            global_data_prop: function(newVal, oldVal) { 
                // console.log('Prop changed: ', newVal, ' | was: ', oldVal)
                this.data = this.global_data_prop
            },
            category_prop: function(newVal, oldVal) { 
                this.category = this.category_prop
                if (this.category == 'outdoor') {
                    this.get_regions()
                }
                if (this.category == 'mount_route') {
                    this.get_mount_massive_data()
                }
            },
            // region_id_prop: function(newVal, oldVal) { 
            //     this.data.region_id = this.region_id_prop
            // },
            // mount_id_prop: function(newVal, oldVal) { 
            //     this.data.mount_id = this.mount_id_prop
            // },
        },
        mounted() {
            if (this.category == 'outdoor') {
                this.get_regions()
            }
            if (this.category == 'mount_route') {
                this.get_mount_massive_data()
            }

            // if(!this.region_id == 'select_region'){
            //     this.data.region_id = this.region_id
            // }
            // if(!this.mount_id == 'select_mount'){
            //     this.data.mount_id = this.mount_id
            // }

            this.$emit('global_form_data', this.data)
        },
        methods: {
            get_mount_massive_data(){
                axios
                .get("/mount/mount/")
                .then(response => {
                    this.mount_masive = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_regions(){
                axios
                // .get('/outdoor/regions/'+localStorage.getItem('lang'))
                .get("/outdoor/region/")
                .then(response => {
                    this.regions = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>

</style>