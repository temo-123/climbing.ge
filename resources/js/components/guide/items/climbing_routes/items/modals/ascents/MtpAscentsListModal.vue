<template>
<StackModal
    v-model="is_show_modal"
    :title="$t('guide.mtp_ascent.list_title')"
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

            <p v-else-if="ascents.length === 0" class="text-muted">{{ $t('guide.mtp_ascent.empty') }}</p>

            <table v-else class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ $t('guide.mtp_ascent.col_climber') }}</th>
                        <th>{{ $t('guide.mtp_ascent.date_label') }}</th>
                        <th>{{ $t('guide.mtp_ascent.style_label') }}</th>
                        <th>{{ $t('guide.mtp_ascent.comment_label') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ascent in ascents" :key="ascent.id">
                        <td>{{ ascent.user ? ascent.user.name + ' ' + (ascent.user.surname || '') : $t('guide.route.deleted_user') }}</td>
                        <td>{{ ascent.ascent_date }}<span v-if="ascent.ascent_time"> {{ ascent.ascent_time.slice(0, 5) }}</span></td>
                        <td>{{ ascent.ascent_style ? $t('guide.mtp_ascent.style.' + ascent.ascent_style) : '—' }}</td>
                        <td>
                            {{ ascent.comment || '' }}
                            <a v-if="ascent.photo" :href="'/public/images/mtp_ascents_img/' + ascent.photo" target="_blank" rel="noopener">
                                <i class="fa fa-camera"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</StackModal>
</template>

<script>
export default {
    emits: ['back_mtp_modal'],
    data() {
        return {
            is_show_modal: false,
            mtp_id: 0,
            ascents: [],
            is_loading: false,
        };
    },
    methods: {
        show_mtp_ascents_modal(mtp_id) {
            this.mtp_id = mtp_id;
            this.ascents = [];
            this.is_loading = true;
            this.is_show_modal = true;
            axios.get('/get_mtp_ascent/get_mtp_ascents/' + mtp_id)
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
            this.$emit('back_mtp_modal', this.mtp_id);
        },
    },
};
</script>
