<template>
    <stack-modal
            :show="is_show_edit_modal"
            :title="'Edit Pitch'"
            @close="close_modal()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <div class="col-md-12">
    
                <div class="row justify-content-center" v-if="is_loading">
                    <div class="col-md-4">
                        <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>
            
                <div class="form-group clearfix row" v-if="problem_status != ''">
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            {{ problem_status }}
                        </div>
                    </div>
                </div>
            
                <div class="wrapper container-fluid container" v-if="!is_loading">
                    <form id="mtp_edit_form" @submit.prevent="save()">
                
                        <div class="form-group clearfix row" v-if="errors.sector_id">
                            <div class="col-md-12">
                                <div class="alert alert-danger" role="alert">
                                {{ errors.sector_id[0] }}
                                </div>
                            </div>
                        </div>
                
                        <div class="form-group clearfix row">
                            <h2> Grade </h2>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-5">
                                <select class="form-control"  v-model="data.category" required>
                                    <option value="" disabled>Please select mtp type</option>
                                    <option value="sport climbing">Sport climbing</option>
                                    <option value="tred">Tred Climbing</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group clearfix row" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'">
                            <div class="col-md-6">
                                <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.grade" required>
                                    <option value=""> No grade </option>
                                    <option value="Project">Project</option>
                                    <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.or_grade">
                                    <option value=""> No grade </option>
                                    <option value="Project">Project</option>
                                    <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                                </select>
                            </div>
                        </div>
                
                        <div class="form-group clearfix row" v-if="errors.grade">
                            <div class="col-md-12">
                                <div class="alert alert-danger" role="alert">
                                {{ errors.grade[0] }}
                                </div>
                            </div>
                        </div>
                
                        <div class="form-group clearfix row">
                            <h2> Route name </h2>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-10">
                            <input type="text" name="name" v-model="data.name" class="form-control" placeholder="Route name.." required> 
                                <div class="alert alert-danger" role="alert" v-if="errors.name">
                                {{ errors.name[0] }}
                                </div>
                            </div>
                        </div>
                
                        <div class="form-group clearfix row">
                            <h2> Description (Text) </h2>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-10">
                                <ckeditor v-model="data.text" :config="description_editor"></ckeditor>
                            </div>
                        </div>
                
                        <div class="form-group clearfix row">
                            <h2> Bolts & height </h2>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-6" v-if="data.category != '' && data.category == 'sport climbing'">
                                <input type="number" name="title" v-model="data.bolts" class="form-control" placeholder="Bolts"> 
                            </div>
                
                            <div class="col-md-6">
                                <input type="number" name="title" class="form-control" v-model="data.height" placeholder="Height"> 
                            </div>
                
                        </div>
                
                        <div class="form-group clearfix row">
                            <h2> Author & Creation date </h2>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-6">
                                <input type="text" name="title" class="form-control" v-model="data.author" placeholder="Bolter"> 
                            </div>
                            <div class="col-md-6">
                                <input type="date" name="title" class="form-control" v-model="data.creation_data" placeholder="Bolting Data"> 
                            </div>
                        </div>
                
                        <div class="form-group clearfix row">
                            <h2>Firs Ascent </h2>
                        </div>

                        <div class="form-group clearfix row">
                            <div class="col-md-6">
                                <input type="text" name="title" class="form-control" v-model="data.first_ascent" placeholder="First ascent"> 
                            </div>
                        </div>
        
                    </form>
                </div>
            </div>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form='mtp_edit_form'
                    :class="{'btn btn-primary': true}"
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
    
    import Editor from '../../../../../items/canvas/EditorComponent.vue'
    import { editor_config } from '../../../../../../../mixins/editor/editor_config_mixin.js'

    export default {
        components: {
            StackModal,
            Editor,
            validator_alerts_component
        },
        mixins: [
            editor_config
        ],
        data() {
            return {
                description_editor: editor_config.get_small_editor_config(),
                errors: [],
                status: "",
                problem_status: "",

                is_loading: false,

                editing_pitch_id: null,
        
                data: {
                    // mtp_id: "",
            
                    grade: "",
                    or_grade: "",
            
                    name: "",
                    text: "",
                    
                    height: "",
                    bolts: "",
            
                    author: "",
                    creation_data: "",
                    first_ascent: "",
            
                    anchor_type: "",
                    category: "",
                },
        
                go_back_action: false,

                sport_route_grade: [
                    "4",
                    "5a", "5b", "5c", "5c+",
                    "6a", "6a+", "6b", "6b+", "6c", "6c+",
                    "7a", "7a+", "7b", "7b+", "7c", "7c+",
                    "8a", "8a+", "8b", "8b+", "8c", "8c+",
                    "9a", "9a+", "9b", "9b+", "9c", "9c+",
                ],
                is_show_edit_modal: false,
            }
        },
    
        mounted() {
            // this.get_region_data()
        },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action_query == true){
                if (window.confirm('edited information will be deleted!!! Are you sure, you want go back?')) {
                    this.is_back_action_query = false;
                    next()
                } else {
                    next(false)
                }
            }else {
                next()
            }
        },
    
        methods: {
            show_modal(id){
                this.editing_pitch_id = id
                this.is_show_edit_modal = true

                this.get_editing_mtp_data(id)
            },
            close_modal(){
                this.is_show_edit_modal = false

                this.clear_form()
            },

            get_editing_mtp_data: function(id){
                this.is_loading = true

                axios
                .get("/mtp/mtp_pitch/get_editin_pitch/" + id)
                .then(response => {
                    this.data = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },
            save: function () {
                this.is_loading = true
                axios
                .post('/mtp/mtp_pitch/mtp_pitch_edit/' + this.editing_pitch_id, {
                    data: this.data,
                })
                .then(response => {
                    this.close_modal()
                    this.$emit('update')
                })
                .catch(error =>{
                    this.status = "error"
                })
                .finally(() => this.is_loading = false);
            },
        
            clear_form(){
                this.data = {
                    mtp_id: this.data.mtp_id,
                    category: this.data.category,
            
                    grade: "",
                    or_grade: "",
            
                    name: "",
                    text: "",
                    
                    height: "",
                    bolts: "",
            
                    author: "",
                    creation_data: "",
                    first_ascent: "",
                }
            },
        }
    }
</script>