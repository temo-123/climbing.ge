<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <tabsComponent
                        :table_data="data_for_tab"
                        @show_ascent_detail="show_ascent_detail"
                        @del_ascent="del_ascent"
                    />
                </div>
            </div>
        </div>

        <AscentDetailModal ref="ascentDetailModal" />
    </div>
</template>

<script>
    import tabsComponent from '../../items/data_table/TabsComponent.vue'
    import breadcrumb    from '../../items/BreadcrumbComponent.vue'
    import AscentDetailModal from '../../items/modal/AscentDetailModal.vue'

    export default {
        components: { tabsComponent, breadcrumb, AscentDetailModal },

        data() {
            return {
                data_for_tab: [],
                ascents: [],
            }
        },

        mounted() {
            this.load_ascents()
        },

        methods: {
            load_ascents() {
                axios.get('/get_user_ascents/get_all_my_ascents')
                    .then(response => {
                        this.ascents = response.data
                        this.data_for_tab = [{
                            id: 1,
                            table_name: 'My Ascents',
                            tab_data: {
                                data: response.data,
                                tab: {
                                    head: [
                                        'Summit',
                                        'Date',
                                        'Route',
                                        'GPS',
                                        'Details',
                                        'Delete',
                                    ],
                                    body: [
                                        ['data', ['summit', 'title']],
                                        ['data', ['ascent_date']],
                                        ['data', ['route', 'name']],
                                        ['data', ['is_gps_validated'], 'bool'],
                                        ['action_fun_id', 'show_ascent_detail', 'btn btn-info btn-sm',  '<i aria-hidden="true" class="fa fa-eye"></i>',    ['id']],
                                        ['action_fun_id', 'del_ascent',         'btn btn-danger btn-sm', '<i aria-hidden="true" class="fa fa-trash"></i>', ['id']],
                                    ],
                                    perm: [
                                        ['no'], ['no'], ['no'], ['no'], ['no'], ['no'],
                                    ],
                                },
                            },
                        }]
                    })
                    .catch(error => console.log(error))
            },

            show_ascent_detail(id) {
                const ascent = this.ascents.find(a => a.id == id)
                if (ascent) this.$refs.ascentDetailModal.show_modal(ascent)
            },

            del_ascent(id) {
                if (!confirm('Delete this ascent?')) return
                axios.delete('/del_ascent/' + id)
                    .then(() => this.load_ascents())
                    .catch(error => console.log(error))
            },
        },
    }
</script>
