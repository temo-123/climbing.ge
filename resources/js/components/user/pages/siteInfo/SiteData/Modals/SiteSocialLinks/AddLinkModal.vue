<template>
    <StackModal
            :show="is_show_modal"
            :title="$t('admin.site_info.add_social_link_title')"
            @close="close_modal"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <validator_alerts_component
                :errors_prop="error"
            />

            <form method="POST" id="add_social_link_form" v-on:submit.prevent="add_social_link">
                <input type="text" class="form-control" v-model="data.title" name="title" id="Title" :placeholder="$t('common.title')" title="Title">
                <input type="url" class="form-control" v-model="data.url" name="url" id="url" pattern="https://.*|http://.*" :placeholder="$t('admin.site_info.url_placeholder')" required>
            </form>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    :class="{'btn btn-primary': true}"
                    form="add_social_link_form"
                >
                {{ $t('common.save') }}
                </button>
            </div>
        </div>
    </StackModal>
</template>


<script>
    // import StackModal from '@innologica/vue-stackable-modal'  // Global now

    // import validator_alerts_component from '../../../../../items/validator_alerts_component.vue'

    export default {
        components: {
            // validator_alerts_component,
            // StackModal,
        },

        props: ['show'],

        data() {
            return {
                data: {
                    title: '',
                    url: ''
                },

                error: [],
                is_show_modal: false,
            }
        },
        mounted() {
            //
        },
        methods: {
            add_social_link(){
                axios
                .post('/set_site_social_links/add_site_social_links', {        
                    data: this.data,
                    _method: 'post'
                })
                .then(response => {
                    // Success - emit event to parent and close modal
                    this.$emit('update')
                    this.close_modal()
                })
                .catch(err => {
                    if (err.response && err.response.status == 422) {
                        this.error = err.response.data.validation
                    } else {
                        console.log(err);
                    }
                })
            },
            close_modal(){
                this.is_show_modal = false
                this.data = {
                    title: '',
                    url: ''
                }
                this.error = []
            },
            show_modal(){
                this.is_show_modal = true
            }
        }
    }
</script>