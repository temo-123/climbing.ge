<template>
<StackModal
    v-model="is_show_modal"
    :title="$t('guide.route_ascent.list_title')"
    @close="close_modal"
    :saveButton="{ visible: false }"
    :cancelButton="{
        title: $t('guide.route.back_to_modal'),
        btnClass: { 'btn btn-warning': true },
    }"
>
    <div class="model-body">
        <div class="container">
            <div v-if="is_loading" class="text-center py-3 text-muted">{{ $t('global.loading') }}</div>

            <p v-else-if="ascents.length === 0" class="text-muted">{{ $t('guide.route_ascent.empty') }}</p>

            <template v-else>
                <p class="ascent_summary">
                    <span v-for="(count, style) in style_counts" :key="style" class="label label-default">
                        {{ $t('guide.route_ascent.style.' + style) }}: {{ count }}
                    </span>
                    <span v-if="grade_consensus" class="label label-info">
                        {{ $t('guide.route_ascent.grade_consensus') }}: {{ grade_consensus }}
                    </span>
                </p>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ $t('guide.route_ascent.col_climber') }}</th>
                            <th>{{ $t('guide.route_ascent.date_label') }}</th>
                            <th>{{ $t('guide.route_ascent.style_label') }}</th>
                            <th>{{ $t('guide.route_ascent.col_grade') }}</th>
                            <th>{{ $t('guide.route_ascent.comment_label') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ascent in ascents" :key="ascent.id">
                            <td>{{ ascent.user ? ascent.user.name + ' ' + (ascent.user.surname || '') : $t('guide.route.deleted_user') }}</td>
                            <td>{{ ascent.ascent_date }}</td>
                            <td>
                                {{ $t('guide.route_ascent.style.' + ascent.ascent_style) }}
                                <small v-if="ascent.attempts && ascent.attempts > 1" class="text-muted">({{ $t('guide.route_ascent.attempts_short', { count: ascent.attempts }) }})</small>
                            </td>
                            <td>{{ ascent.suggested_grade || '—' }}</td>
                            <td>
                                {{ ascent.comment || '' }}
                                <a v-if="ascent.photo" :href="'/public/images/route_ascents_img/' + ascent.photo" target="_blank" rel="noopener">
                                    <i class="fa fa-camera"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </div>
    </div>
</StackModal>
</template>

<script>
export default {
    emits: ['back_route_modal'],
    data() {
        return {
            is_show_modal: false,
            route_id: 0,
            ascents: [],
            is_loading: false,
        };
    },
    computed: {
        style_counts() {
            const counts = {};
            this.ascents.forEach(ascent => {
                counts[ascent.ascent_style] = (counts[ascent.ascent_style] || 0) + 1;
            });
            return counts;
        },
        // Most frequently suggested grade (only climbers who disagreed with the topo grade suggest one)
        grade_consensus() {
            const counts = {};
            this.ascents.forEach(ascent => {
                if (ascent.suggested_grade) counts[ascent.suggested_grade] = (counts[ascent.suggested_grade] || 0) + 1;
            });
            const grades = Object.keys(counts);
            if (grades.length === 0) return null;
            return grades.sort((a, b) => counts[b] - counts[a])[0];
        },
    },
    methods: {
        show_route_ascents_modal(route_id) {
            this.route_id = route_id;
            this.ascents = [];
            this.is_loading = true;
            this.is_show_modal = true;
            axios.get('/get_route_ascent/get_route_ascents/' + route_id)
                .then(response => {
                    this.ascents = response.data;
                })
                .catch(() => {
                    this.ascents = [];
                })
                .finally(() => {
                    this.is_loading = false;
                });
        },

        close_modal() {
            this.is_show_modal = false;
            this.$emit('back_route_modal', this.route_id);
        },
    },
};
</script>

<style scoped>
.ascent_summary .label {
    display: inline-block;
    margin: 0 6px 6px 0;
    font-size: 1.2rem;
}
</style>
