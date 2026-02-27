<template>
    <stack-modal
            :show="is_show_modal"
            title="Add locale data"
            @close="close_model"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <validator_alerts_component
                :errors_prop="error"
            />

            <form method="POST" id="add_site_locale_data_form" v-on:submit.prevent="add_site_local_data">
                <input type="text" class="form-control" v-model="data.slug" name="slug" id="Title" placeholder="Title" slug="Title">

                <ckeditor v-model="data.ka_data" :config="editor_config.ka_data"></ckeditor>
                <ckeditor v-model="data.us_data" :config="editor_config.us_data"></ckeditor>
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    :class="{'btn btn-primary': true}"
                    form="add_site_locale_data_form"
                >
                Save
                </button>
            </div>
        </div>
    </stack-modal>
</template>


<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import validator_alerts_component from '../../../../../items/validator_alerts_component.vue'

    export default {
        components: {
            validator_alerts_component,
            StackModal,
        },

        props: [
            // 'show'
        ],

        data() {
            return {
                data: {
                    slug: '',
                    ka_data: '',
                    us_data: '',
                },

                error: [],

                editor_config: {
                    us_data: this.$editor_config.get_big_editor_config(),
                    ka_data: this.$editor_config.get_big_editor_config(),
                },

                is_show_modal: false,
            }
        },

        mounted() {
            //
        },

        methods: {
            add_site_local_data(){
                axios
                .post('/set_site_data/add_site_local_data', {        
                    data: this.data,
                    _method: 'post'
                })
                .then(response => {
                    // Success - emit event to parent and close modal
                    this.$emit('update')
                    this.close_model()
                })
                .catch(err => {
                    if (err.response && err.response.status == 422) {
                        this.error = err.response.data.validation
                    } else {
                        console.log(err);
                    }
                })
            },
            close_model(){
                this.is_show_modal = false
                this.data = {
                    slug: '',
                    ka_data: '',
                    us_data: '',
                }
                this.error = []
            },
            show_modal(){
                this.is_show_modal = true
            }
        }
    }
</script>