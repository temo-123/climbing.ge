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
                        @update="load_ascents"
                        @show_ascent_detail="show_ascent_detail"
                        @open_track="open_track"
                        @edit_ascent="edit_ascent"
                        @del_ascent="del_ascent"
                        @delete_selected="bulk_delete_ascents"
                    />
                </div>
            </div>
        </div>

        <AscentDetailModal ref="ascentDetailModal" />
        <EditAscentModal ref="editAscentModal" @saved="load_ascents" />
        <TrackVerifyModal ref="trackModal" @saved="load_ascents" />
    </div>
</template>

<script>
    import tabsComponent from '../../items/data_table/TabsComponent.vue'
    import breadcrumb    from '../../items/BreadcrumbComponent.vue'
    import AscentDetailModal from '../../items/modal/AscentDetailModal.vue'
    import EditAscentModal from '../../items/modal/EditAscentModal.vue'
    import TrackVerifyModal from '../../items/modal/TrackVerifyModal.vue'

    export default {
        components: { tabsComponent, breadcrumb, AscentDetailModal, EditAscentModal, TrackVerifyModal },

        data() {
            return {
                data_for_tab: [],
                ascents: [],
            }
        },

        mounted() {
            this.load_ascents()
            // back from Strava's consent page (StravaCallbackController)
            const strava = this.$route.query.strava
            if (strava) {
                this.$bus.$emit('toast', strava === 'connected'
                    ? { type: 'success', message: this.$t('user.track.strava_connected') }
                    : { type: 'warning', duration: 7000, message: this.$t('user.track.strava_failed') })
                const query = { ...this.$route.query }
                delete query.strava
                this.$router.replace({ query })
            }
        },

        methods: {
            load_ascents() {
                axios.get('/get_user_ascents/get_all_my_ascents')
                    .then(response => {
                        this.ascents = response.data
                        this.data_for_tab = [{
                            id: 1,
                            table_name: this.$t('user.ascents.table_name'),
                            tab_data: {
                                data: response.data,
                                tab: {
                                    head: [
                                        this.$t('common.summit'),
                                        this.$t('common.date'),
                                        this.$t('common.route'),
                                        this.$t('user.ascents.col_gps'),
                                        this.$t('user.track.col_track'),
                                        this.$t('user.ascents.col_archive'),
                                        this.$t('user.ascents.col_details'),
                                        this.$t('user.track.col_action'),
                                        this.$t('user.ascents.col_edit'),
                                        this.$t('common.delete'),
                                    ],
                                    body: [
                                        ['data', ['summit', 'title']],
                                        ['data', ['ascent_date']],
                                        ['data', ['route_name']],
                                        ['data', ['is_gps_validated'], 'bool'],
                                        ['data', ['track_verified'], 'bool'],
                                        ['data', ['is_archive'], 'bool'],
                                        ['action_fun_id', 'show_ascent_detail', 'btn btn-info btn-sm',    '<i aria-hidden="true" class="fa fa-eye"></i>',    ['id']],
                                        ['action_fun_id', 'open_track',         'btn btn-success btn-sm', '<i aria-hidden="true" class="fa fa-map-signs"></i>', ['id']],
                                        ['action_fun_id', 'edit_ascent',        'btn btn-warning btn-sm', '<i aria-hidden="true" class="fa fa-pencil"></i>', ['id']],
                                        ['action_fun_id', 'del_ascent',         'btn btn-danger btn-sm',  '<i aria-hidden="true" class="fa fa-trash"></i>',  ['id']],
                                    ],
                                    perm: [
                                        ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'],
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

            // GPS track: GPX upload / Strava import (TrackVerifyModal)
            open_track(id) {
                const ascent = this.ascents.find(a => a.id == id)
                if (ascent) this.$refs.trackModal.open(ascent)
            },

            edit_ascent(id) {
                const ascent = this.ascents.find(a => a.id == id)
                if (ascent) this.$refs.editAscentModal.show_modal(ascent)
            },

            del_ascent(id) {
                if (!confirm(this.$t('user.ascents.confirm_delete'))) return
                axios.delete('/get_user_ascents/del_ascent/' + id)
                    .then(() => this.load_ascents())
                    .catch(error => console.log(error))
            },

            bulk_delete_ascents(ids) {
                axios.post('/get_user_ascents/bulk_delete', { ids })
                    .then(() => this.load_ascents())
                    .catch(error => console.log(error))
            },
        },
    }
</script>
