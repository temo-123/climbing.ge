<template>
    <StackModal
            :show="is_show_modal"
            title="Add locale data"
            @close="close_model"
            @save="$refs.add_site_locale_form.requestSubmit()"
            :saveButton="{ visible: true, title: 'Save' }"
            :cancelButton="{ visible: false }"
        >
        <div>
            <validator_alerts_component
                :errors_prop="error"
            />

            <form ref="add_site_locale_form" id="add_site_locale_data_form" v-on:submit.prevent="add_site_local_data">
                <input type="text" class="form-control" v-model="data.slug" name="slug" id="Title" placeholder="Title" slug="Title">

                <big_editor v-model="data.ka_data" />
                <big_editor v-model="data.us_data" />
            </form>
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
                    us_data: {},
                    ka_data: {},
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
                .post('set_site_data/add_site_local_data', {
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