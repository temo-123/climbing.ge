<template>
    <StackModal
        v-model="is_show_modal"
        title="Edit comment"
        @close="close_modal"
        :saveButton="{ visible: true }" @save="save"
        :cancelButton="{ title: 'Cancel', btnClass: { 'btn btn-secondary': true } }"
    >
        <div class="container">
            <div v-if="is_loading" class="text-center py-3">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
            <div v-else>
                <label class="form-label fw-semibold">Comment text</label>
                <textarea class="form-control" v-model="text" rows="5"></textarea>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_show_modal: false,
            is_loading: false,
            comment_id: null,
            text: '',
        };
    },
    methods: {
        show_modal(id) {
            this.comment_id = id;
            this.text = '';
            this.is_show_modal = true;
            this.fetch();
        },
        close_modal() {
            this.is_show_modal = false;
        },
        fetch() {
            this.is_loading = true;
            axios.get('/set_article/set_guide_comment/get_actyve_comment/' + this.comment_id)
                .then(r => { this.text = r.data.text || ''; })
                .catch(e => console.log(e))
                .finally(() => this.is_loading = false);
        },
        save() {
            axios.post('/set_article/set_guide_comment/user_edit_comment/' + this.comment_id, { text: this.text })
                .then(() => { this.$emit('restart'); this.close_modal(); })
                .catch(e => console.log(e));
        },
    },
};
</script>
