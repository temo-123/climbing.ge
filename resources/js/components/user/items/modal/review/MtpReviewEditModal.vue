<template>
    <StackModal
        v-model="is_show"
        title="Edit MTP review"
        @close="close_modal()"
        :saveButton="{ visible: true }" @save="save_review"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }"
    >
        <div class="model-body">
            <div class="container">
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <img :src="'/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <div class="row" v-show="!is_loading">
                    <form method="POST" id="mtp_review_form" v-on:submit.prevent="save_review" style="width: 100%;">
                        <div class="mb-3">
                            <span>How did you like this route?</span>
                            <starReitingInsert :actyve_stars_prop="data.stars" @get_stars="val => data.stars = val" />
                        </div>

                        <div class="mb-3">
                            <span>What do you think about this route?</span>
                            <textarea
                                id="mtp_feedback"
                                name="mtp_feedback"
                                class="form-control"
                                placeholder="Make feedback"
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
            review_id: null,
            data: {
                stars: 0,
                text: '',
            },
        };
    },
    methods: {
        show_modal(id) {
            this.review_id = id;
            this.is_show = true;
            this.fetch_review();
        },
        close_modal() {
            this.is_show = false;
            this.review_id = null;
            this.data = { stars: 0, text: '' };
        },
        fetch_review() {
            this.is_loading = true;
            axios
                .get('/set_mtp_review/get_actyve_mtp_review/' + this.review_id)
                .then(response => {
                    this.data = {
                        stars: response.data.stars,
                        text: response.data.text,
                    };
                })
                .catch(e => console.log(e))
                .finally(() => this.is_loading = false);
        },
        save_review() {
            this.is_loading = true;
            axios
                .post('/set_mtp_review/edit_mtp_review/' + this.review_id, this.data)
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
