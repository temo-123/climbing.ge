<template>
    <StackModal
            :show="is_show_modal"
            :title="$t('admin.site_info.edit_apps_link_title')"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
            @save="$refs.edit_apps_link_form.requestSubmit()"
            @close="close_modal"
        >
        <validator_alerts_component
            :errors_prop="error"
        />

        <form ref="edit_apps_link_form" id="edit_apps_link_form" v-on:submit.prevent="edit_apps_link">
            <div class="form-group">
                <input type="text" class="form-control" v-model="data.keyword" name="keyword" id="edit_apps_link_keyword" :placeholder="$t('admin.site_info.keyword_placeholder')" required>
                <small class="form-text text-muted">{{ $t('admin.site_info.keyword_hint') }}</small>
            </div>
            <div class="form-group">
                <input type="url" class="form-control" v-model="data.link" name="link" id="edit_apps_link_link" pattern="https://.*|http://.*" :placeholder="$t('admin.site_info.link_placeholder')" required>
            </div>
        </form>
    </StackModal>
</template>


<script>
    export default {
        components: {
            //
        },

        props: [
            // 'show'
        ],

        data() {
            return {
                data: {
                    keyword: '',
                    link: ''
                },
                current_item: null,

                error: [],

                is_show_modal: false,
            }
        },

        mounted() {
            //
        },

        methods: {
            edit_apps_link(){
                axios
                .post('set_apps_links/update/' + this.current_item.id, this.data)
                .then(response => {
                    this.$emit('update')
                    this.close_modal()
                })
                .catch(err => {
                    if (err.response && err.response.status == 422) {
                        this.error = err.response.data.errors
                    } else {
                        console.log(err);
                    }
                })
            },
            close_modal(){
                this.is_show_modal = false
                this.data = {
                    keyword: '',
                    link: ''
                }
                this.current_item = null
                this.error = []
            },
            show_modal_with_data(item){
                this.current_item = item
                this.data.keyword = item.keyword || ''
                this.data.link = item.link || ''
                this.is_show_modal = true
            }
        }
    }
</script>
