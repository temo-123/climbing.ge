<template>
    <StackModal
        v-model="is_show"
        :title="$t('admin.comments.edit_feedback_title')"
        @close="close_modal()"
        :saveButton="{ visible: true }" @save="save_feedback"
        :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-primary': true } }"
    >
        <div class="model-body">
            <div class="container">
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <img :src="'/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <div class="row" v-show="!is_loading">
                    <form method="POST" id="feedback_edit_form" v-on:submit.prevent="save_feedback" style="width: 100%;">
                        <div class="mb-3">
                            <span>{{ $t('admin.comments.rating_label') }}</span>
                            <starReitingInsert :actyve_stars_prop="data.stars" @get_stars="val => data.stars = val" />
                        </div>

                        <div class="mb-3">
                            <span>{{ $t('admin.comments.feedback_text_label') }}</span>
                            <textarea
                                id="feedback_text"
                                name="feedback_text"
                                class="form-control"
                                :placeholder="$t('admin.comments.feedback_text_label')"
                                v-model="data.text"
                                rows="4"
                            ></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </StackModal>
</template>

<script>
import starReitingInsert from '../../../../global_components/StarReitingInsertComponent.vue';

export default {
    components: { starReitingInsert },
    data() {
        return {
            is_show: false,
            is_loading: false,
            feedback_id: null,
            data: {
                stars: 0,
                text: '',
            },
        };
    },
    methods: {
        show_modal(id) {
            this.feedback_id = id;
            this.is_show = true;
            this.fetch_feedback();
        },
        close_modal() {
            this.is_show = false;
            this.feedback_id = null;
            this.data = { stars: 0, text: '' };
        },
        fetch_feedback() {
            this.is_loading = true;
            axios
                .get('/set_product/set_product_feedback/get_actyve_feedback/' + this.feedback_id)
                .then(response => {
                    this.data = {
                        stars: response.data.stars,
                        text: response.data.text,
                    };
                })
                .catch(e => console.log(e))
                .finally(() => this.is_loading = false);
        },
        save_feedback() {
            this.is_loading = true;
            axios
                .post('/set_product/set_product_feedback/edit_feedback/' + this.feedback_id, this.data)
                .then(() => {
                    this.$emit('restart');
                    this.close_modal();
                })
                .catch(e => console.log(e))
                .finally(() => this.is_loading = false);
        },
    },
};
</script>
