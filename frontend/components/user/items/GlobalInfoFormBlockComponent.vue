<template>
    <div class="tabs"> 

        <div  v-if="general_infos.length">
            <div class="row">
                <div class="col-md-3 genera_block_action_but">
                    <input type="radio" :id="locale+'_'+value_name+'_new_info'" name="fav_language" v-model="global_blocks.block_action" value="new_info" @click="block_action('new_info')">
                    <label :for="locale+'_'+value_name+'_new_info'">New info</label><br>
                </div>
                <div class="col-md-3 genera_block_action_but">
                    <input type="radio" :id="locale+'_'+value_name+'_befor'" name="fav_language" v-model="global_blocks.block_action" value="befor" @click="block_action('befor')">
                    <label :for="locale+'_'+value_name+'_befor'">Befor</label><br>
                </div>
                <div class="col-md-3 genera_block_action_but">
                    <input type="radio" :id="locale+'_'+value_name+'_after'" name="fav_language" v-model="global_blocks.block_action" value="after" @click="block_action('after')">
                    <label :for="locale+'_'+value_name+'_after'">After</label><br>
                </div>
                <div class="col-md-3 genera_block_action_but">
                    <input type="radio" :id="locale+'_'+value_name+'_instead'" name="fav_language" v-model="global_blocks.block_action" value="instead" @click="block_action('instead')">
                    <label :for="locale+'_'+value_name+'_instead'">Instead</label>
                </div>
            </div>
        </div>

        <div class="form-group clearfix row">
            <label for="name" class='col-md-2 control-label'> {{ title_prop }} </label>
            <div class="col-md-10">
                <div class="col-md-12" v-if="global_blocks.block_action == 'befor'">
                    <select @change="block_id()" v-model="global_blocks.block_id"  class="form-control"> 
                        <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                    </select> 
                </div>
            
                <div class="col-md-12" v-if="global_blocks.block_action == 'befor' || global_blocks.block_action == 'after' || global_blocks.block_action == 'new_info'">
                    <ckeditor v-model="form_data" :config="text_editor_config" @input="input_event"></ckeditor>
                </div>

                <div class="col-md-12" v-if="global_blocks.block_action == 'after' || global_blocks.block_action == 'instead'">
                    <select @change="block_id()" v-model="global_blocks.block_id"  class="form-control"> 
                        <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                    </select> 
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import { editor_config } from '../../../mixins/editor/editor_config_mixin.js'
    export default {
        mixins: [
            editor_config
        ],
        props: {
            title_prop: {
                type: String,
                default: "Title error"
            },

            value_name_prop: {
                type: String,
                default: ""
            },

            form_data_prop: {
                type: String,
                default: ""
            },
            form_value_name_prop: {
                type: String,
                default: ""
            },

            block_action_prop: {
                type: String,
                default: "new_info"
            },
            block_id_prop: {
                type: Number,
                default: 0
            },

            locale_prop: {
                type: String,
            },

            get_data_in_component_prop: {
                type: Boolean,
                default: true
            },
            global_data_array_prop: {
                type: Array,
                default: []
            },
        },
        data(){
            return {
                text_editor_config: editor_config.get_big_editor_config(),

                general_infos: [],

                form_data: this.form_data_prop,
                locale: this.locale_prop,
                form_value_name: this.form_value_name_prop,
                value_name: this.value_name_prop,

                global_blocks: {
                    block_action: 'new_info',
                    block_id: 0,
                }
            }
        },
        mounted() {
            if(this.get_data_in_component_prop == true){
                this.get_general_info()
            }
            
            this.global_blocks.block_action = this.block_action_prop
            this.global_blocks.block_id = this.block_id_prop
            this.general_infos = this.global_data_array_prop
        },
        watch: {
            // form_data_prop: function(){
            //     this.form_data = this.form_data_prop
            // },
            block_action_prop: function(){
                this.global_blocks.block_action = this.block_action_prop
            },
            block_id_prop: function(){
                this.global_blocks.block_id = this.block_id_prop
            },
            global_data_array_prop: function(){
                if(this.get_data_in_component_prop == false){
                    this.general_infos = this.global_data_array_prop
                }
            },
        },
        methods: {
            get_general_info(){
                if(this.get_data_in_component_prop == true){
                    axios
                    .get('/general_info/')
                    .then(response => {
                        this.general_infos = response.data                
                    })
                    .catch(
                        errors => console.log(errors)
                    );
                }
            },

            input_event(){
                this.$emit('get_form_data', {
                    locale: this.locale,
                    form_data: this.form_data,
                    form_value_name: this.form_value_name
                } )
            },

            block_action(status){
                this.global_blocks.block_action = status
                this.$emit('get_global_blocks_status', {
                    value_name: this.value_name,
                    block_action: this.global_blocks.block_action,
                })
            },

            block_id(){
                this.$emit('get_global_blocks_id', {
                    value_name: this.value_name,
                    block_id: this.global_blocks.block_id
                })
            },
        }
    }
</script>

<style>
.genera_block_action_but label{
    background-color: #ffc9c9;
    border: 1px solid #424242 !important;
}
</style>