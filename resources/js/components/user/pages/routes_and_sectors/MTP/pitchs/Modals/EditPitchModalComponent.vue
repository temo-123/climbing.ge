<template>
    <stack-modal
            :show="is_show_edit_modal"
            :title="'Edit Pitch'"
            @close="close_modal()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <form id="mtp_edit_form" @submit.prevent="save()">
                <select class="form-control"  v-model="data.category" required>
                    <option value="" disabled>Please select mtp type</option>
                    <option value="sport climbing">Sport climbing</option>
                    <option value="tred">Tred Climbing</option>
                </select>
                <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.grade" required>
                    <option value=""> No grade </option>
                    <option value="Project">Project</option>
                    <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>
                <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.or_grade">
                    <option value=""> No grade </option>
                    <option value="Project">Project</option>
                    <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>

                <input type="text" name="name" v-model="data.name" class="form-control" placeholder="name"> 
                <input type="number" name="bolts" v-model="data.bolts" class="form-control" placeholder="Bolts"> 
                <input type="number" name="height" class="form-control" v-model="data.height" placeholder="Height"> 
                <input type="text" name="auther" class="form-control" v-model="data.author" placeholder="Bolter"> 
                <input type="date" name="creation_data" class="form-control" v-model="data.creation_data" placeholder="Bolting Data"> 
                <input type="text" name="first_ascent" class="form-control" v-model="data.first_ascent" placeholder="First ascent"> 

                <ckeditor v-model="data.text" :config="description_editor"></ckeditor>
            </form>
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
                .get("/set_mtp/set_mtp_pitch/get_editin_pitch/" + id)
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
                .post('/set_mtp/set_mtp_pitch/mtp_pitch_edit/' + this.editing_pitch_id, {
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