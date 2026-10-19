<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row" v-if="stats.total > 0">
                <div class="col-sm-12">
                    <div class="route_ascent_stats">
                        <span class="badge bg-secondary">{{ $t('user.route_ascents.total') }}: {{ stats.total }}</span>
                        <span class="badge bg-success" v-if="stats.hardest">{{ $t('user.route_ascents.hardest') }}: {{ stats.hardest }}</span>
                        <span class="badge bg-success" v-if="stats.hardest_boulder">{{ $t('user.route_ascents.hardest_boulder') }}: {{ stats.hardest_boulder }}</span>
                        <span class="badge bg-info text-dark" v-for="(count, style) in stats.styles" :key="style">
                            {{ $t('guide.route_ascent.style.' + style) }}: {{ count }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <tabsComponent
                        :table_data="data_for_tab"
                        @update="load_ascents"
                        @del_ascent="del_ascent"
                        @delete_selected="bulk_delete_ascents"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent from '../../items/data_table/TabsComponent.vue'
    import breadcrumb    from '../../items/BreadcrumbComponent.vue'

    // French sport grades in ascending order — used to find the hardest clean lead send
    const SPORT_GRADES = [
        '4', '5a', '5a+', '5b', '5b+', '5c', '5c+',
        '6a', '6a+', '6b', '6b+', '6c', '6c+',
        '7a', '7a+', '7b', '7b+', '7c', '7c+',
        '8a', '8a+', '8b', '8b+', '8c', '8c+',
        '9a', '9a+', '9b', '9b+', '9c',
    ]

    export default {
        components: { tabsComponent, breadcrumb },

        data() {
            return {
                data_for_tab: [],
                stats: { total: 0, hardest: null, hardest_boulder: null, styles: {} },
            }
        },

        mounted() {
            this.load_ascents()
        },

        methods: {
            load_ascents() {
                axios.get('/set_route_ascent/get_my_route_ascents')
                    .then(response => {
                        const ascents = response.data.map(ascent => ({
                            ...ascent,
                            style_name: this.$t('guide.route_ascent.style.' + ascent.ascent_style),
                        }))
                        this.stats = this.build_stats(ascents)

                        this.data_for_tab = [{
                            id: 1,
                            table_name: this.$t('user.route_ascents.my_table_name'),
                            tab_data: {
                                data: ascents,
                                tab: {
                                    head: [
                                        this.$t('user.route_ascents.col_route'),
                                        this.$t('user.route_ascents.col_grade'),
                                        this.$t('user.route_ascents.col_sector'),
                                        this.$t('common.date'),
                                        this.$t('user.route_ascents.col_style'),
                                        this.$t('user.route_ascents.col_attempts'),
                                        this.$t('user.route_ascents.col_suggested_grade'),
                                        this.$t('user.route_ascents.col_comment'),
                                        this.$t('common.delete'),
                                    ],
                                    body: [
                                        ['data', ['route_name']],
                                        ['data', ['route_grade']],
                                        ['data', ['sector_name']],
                                        ['data', ['ascent_date']],
                                        ['data', ['style_name']],
                                        ['data', ['attempts']],
                                        ['data', ['suggested_grade']],
                                        ['data', ['comment']],
                                        ['action_fun_id', 'del_ascent', 'btn btn-danger btn-sm', '<i aria-hidden="true" class="fa fa-trash"></i>', ['id']],
                                    ],
                                    perm: [
                                        ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no'],
                                    ],
                                },
                            },
                        }]
                    })
                    .catch(error => console.log(error))
            },

            // Hardest = highest-graded onsight/flash/redpoint (toprope doesn't count as a send)
            build_stats(ascents) {
                const styles = {}
                let hardest_index = -1
                let hardest_boulder = -1
                ascents.forEach(ascent => {
                    styles[ascent.ascent_style] = (styles[ascent.ascent_style] || 0) + 1
                    if (ascent.ascent_style == 'toprope' || !ascent.route || !ascent.route.grade) return
                    const boulder = /^V(\d+)/i.exec(ascent.route.grade)
                    if (boulder) {
                        hardest_boulder = Math.max(hardest_boulder, parseInt(boulder[1]))
                        return
                    }
                    hardest_index = Math.max(hardest_index, SPORT_GRADES.indexOf(ascent.route.grade))
                })
                return {
                    total: ascents.length,
                    hardest: hardest_index >= 0 ? SPORT_GRADES[hardest_index] : null,
                    hardest_boulder: hardest_boulder >= 0 ? 'V' + hardest_boulder : null,
                    styles,
                }
            },

            del_ascent(id) {
                if (!confirm(this.$t('user.ascents.confirm_delete'))) return
                axios.delete('/set_route_ascent/del_my_route_ascent/' + id)
                    .then(() => this.load_ascents())
                    .catch(error => console.log(error))
            },

            bulk_delete_ascents(ids) {
                Promise.all(ids.map(id => axios.delete('/set_route_ascent/del_my_route_ascent/' + id)))
                    .finally(() => this.load_ascents())
            },
        },
    }
</script>

<style scoped>
.route_ascent_stats {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 12px;
}
.route_ascent_stats .badge {
    font-size: 0.95rem;
}
</style>
