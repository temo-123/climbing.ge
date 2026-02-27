<template>
    <stack-modal
            :show="is_show_modal"
            title="Add site social link"
            @close="close_modal"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <validator_alerts_component
                :errors_prop="error"
            />

            <form method="POST" id="add_social_link_form" v-on:submit.prevent="add_social_link">
                <input type="text" class="form-control" v-model="data.title" name="title" id="Title" placeholder="Title" title="Title">
                <input type="url" class="form-control" v-model="data.url" name="url" id="url" pattern="https://.*|http://.*" placeholder="URL" required>
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    :class="{'btn btn-primary': true}"
                    form="add_social_link_form"
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