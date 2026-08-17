<template>
    <StackModal
            :show="is_show_modal"
            :title="$t('admin.site_info.add_apps_link_title')"
            @close="close_modal"
            @save="$refs.add_apps_link_form.requestSubmit()"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <validator_alerts_component
                :errors_prop="error"
            />

            <form ref="add_apps_link_form" id="add_apps_link_form" v-on:submit.prevent="add_apps_link">
                <div class="form-group">
                    <input type="text" class="form-control" v-model="data.keyword" name="keyword" id="apps_link_keyword" :placeholder="$t('admin.site_info.keyword_placeholder')" required>
                    <small class="form-text text-muted">{{ $t('admin.site_info.keyword_hint') }}</small>
                </div>
                <div class="form-group">
                    <input type="url" class="form-control" v-model="data.link" name="link" id="apps_link_link" pattern="https://.*|http://.*" :placeholder="$t('admin.site_info.link_placeholder')" required>
                </div>
            </form>
        </div>
    </StackModal>
</template>


<script>
    export default {
        components: {
            //
        },

        props: ['show'],

        data() {
            return {
                data: {
                    keyword: '',
                    link: ''
                },

                error: [],
                is_show_modal: false,
            }
        },
        mounted() {
            //
        },
        methods: {
            add_apps_link(){
                axios
                .post('set_apps_links/create', this.data)
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
                this.error = []
            },
            show_modal(){
                this.is_show_modal = true
            }
        }
    }
</script>
