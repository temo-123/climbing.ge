<template>
    <stack-modal
            :show="is_show_add_modal"
            :title="'Add tour category'"
            @close="close_modal()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <h1>Add tour category</h1>

            <div class="row justify-content-center" v-show="is_loading">
                <div class="col-md-4">
                    <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                </div>
            </div>

            <div class="row" v-show="!is_loading">
                <div class="col-md-12" v-if="error.length != 0">
                    <div class="alert alert-danger" role="alert" v-if="error.us_name">
                        English name - {{ error.us_name[0] }}
                    </div>
                    <div class="alert alert-danger" role="alert" v-if="error.ka_name">
                        Georgian name - {{ error.ka_name[0] }}
                    </div>
                    <!-- <div class="alert alert-danger" role="alert" v-if="error.ru_name">
                        Russian name - {{ error.ru_name[0] }}
                    </div> -->
                </div>
            </div>
            <div v-show="!is_loading">
                <p>English name</p>
                <input type="text" class="form-control" v-model="form_data.us_name" name="English name" id="English name" placeholder="Enter English name" title="Enter English name">
                
                <p>Georgian name</p>
                <input type="text" class="form-control" v-model="form_data.ka_name" name="English name" id="English name" placeholder="Enter English name" title="Enter English name">
                
                <!-- <p>Russian name</p>
                <input type="text" class="form-control" v-model="form_data.ru_name" name="English name" id="English name" placeholder="Enter English name" title="Enter English name"> -->
            </div>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="button"
                    :class="{'btn btn-primary': true}"
                    @click="add_tour_category()"
                >
                Update
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
    
        data() {
            return {
                form_data: {
                    us_name: '',
                    ka_name: '',
                    ru_name: '',
                },
                is_show_add_modal: false,
                error: [],
                is_loading: false,
            }
        },

        mounted() {
            //
        },
         
        methods: {
            close_modal(){
                this.is_show_add_modal = false
                this.form_data = {
                    us_name: '',
                    ka_name: '',
                    ru_name: '',
                }
            },
            open_modal(){
                this.is_show_add_modal = true
                this.form_data = {
                    us_name: '',
                    ka_name: '',
                    ru_name: '',
                }
            },
            add_tour_category: function(){
                this.is_loading = true,

                axios
                .post("/tour/category/add_category/", {
                    data: this.form_data,

                    _method: 'Post'
                })
                .then(response => {
                    this.update()
                    this.close_modal()
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.errors
                    }
                })
                .finally(
                    this.is_loading = false,
                )
            },
            update(){
                this.$emit('restart')
            }
        }
    }
</script>