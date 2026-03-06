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
                        @update="get_sectors"
                        @del_sector_local_images="del_sector_local_images"
                        @show_local_image_modal="show_local_image_modal"
                    />
                </div>
            </div>
        </div>
        <sectorLocalImageModal ref="sector_local_image_modal" />
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'

    import sectorLocalImageModal from "../../items/modal/tab_modals/SectorLocalImagesModalComponent"
    export default {
        components: {
            tabsComponent,
            breadcrumb,
            sectorLocalImageModal
        },
        // props: [
        //     'status',
        // ],
        data(){
            return {
                data_for_tab: [],
            }
        },
        mounted() {
            this.get_sectors()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_sectors()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_sectors(){
                axios
                .get("/get_sector/get_sector_local_images/get_all_sector_local_images/")
                .then(response => {
                    this.data_for_tab = [];

                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Sectors local images', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'sectorLocalImagesListAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Options',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action_id', ['title'], 'show_local_image_modal'],
                                                        ['action_router', 'sectorLocalImagesOptions', 'btn btn-success', '<i class="fa fa-list" aria-hidden="true"></i>'],
                                                        ['action_router', 'sectorLocalImagesListEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_sector_local_images', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['sector_local_images', 'edit'],
                                                        ['sector_local_images', 'edit'],
                                                        ['sector_local_images', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
            del_sector_local_images(id){
                if(confirm('Are you sure, you want delite itt?')){
                    axios
                    .post('/set_sector/set_sector_local_images/del_locale_image/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_sectors()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_local_image_modal(id){
                this.$refs.sector_local_image_modal.show_sector_local_image_modal(id)
            }
        }
    }
</script>

<style>

</style>