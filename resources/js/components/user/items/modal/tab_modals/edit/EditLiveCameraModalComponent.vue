<template>
    <stack-modal
            :show="is_live_camera_edit_model"
            title="Edit order live_camera"
            @close="close_modal"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <form v-on:submit.prevent="edit_live_camera" id="edit_live_camera">
                <input type="text" class="form-control" v-model="data.name" name="Name" placeholder="Name" title="Name" required>
                <input type="link" class="form-control" v-model="data.link" name="link" placeholder="link" title="link" required>

                <select class="form-control" v-model="data.published" name="published" required> 
                    <option :value="0">Not public</option> 
                    <option :value="1">Public</option> 
                </select> 

                <select
                    class="form-control"
                    name="article_id"
                    v-model="data.article_id"
                    required
                >
                    <option
                        v-bind:value="0"
                        disabled
                    >
                        Select outdoor area
                    </option>
                    <option
                        v-for="region in regions"
                        :key="region"
                        v-bind:value="region.id"
                    >
                        {{ region.url_title }}
                    </option>
                </select>
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="edit_live_camera"
                    :class="{'btn btn-primary': true}"
                >
                edit live_camera
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
        props: [
            'table_info',
        ],
        mounted(){
            // console.log(this.table_info)
            // this.get_region_data()
        },
        data(){
            return{
                is_live_camera_edit_model: false,
                is_live_camera_edit_model: false,

                editing_camera_id: 0,

                regions: [],
                data: {
                    name: '',
                    link: '',
                    published: 0,
                    article_id: 0
                }
            }
        },
        methods: {
            get_region_data: function () {
                axios
                .get("/get_article/get_category_articles/ice")
                .then(response => {
                    this.regions = response.data
                })
            },
            get_editing_region_data: function () {
                axios
                .get("/set_live_camera/get_editing_live_camera/" + this.editing_camera_id)
                .then(response => {
                    this.data = response.data

                    this.is_live_camera_edit_model = true
                })
            },
            edit_live_camera(){
                    axios
                    .post('/set_live_camera/edit_live_camera/' + this.editing_camera_id, {
                        data: this.data,
                        _method: 'post',
                    })
                    
                    .then(Response => {
                        alert('Edited successfully')
                        this.$emit('update')
                        this.is_live_camera_edit_model = false
                        this.clear_form()
                    })
                    .catch(error => console.log(error))
            },
            clear_form(){
                this.data = {
                    name: '',
                    link: '',
                    published: 0,
                    article_id: 0
                }
            },
            show_modal(id){
                // this.is_live_camera_edit_model = true

                this.editing_camera_id = id

                this.get_region_data()
                this.get_editing_region_data()
            },
            close_modal(){
                if(confirm('Are you sure, you want close form? All data whil deleted!')){
                    this.is_live_camera_edit_model = false
                    this.clear_form()
                }
            }
        }
    }
</script>

<style>

</style>