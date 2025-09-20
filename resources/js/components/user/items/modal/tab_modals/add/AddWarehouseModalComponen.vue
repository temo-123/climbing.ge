<template>
    <stack-modal
            :show="is_warehouse_add_model"
            title="Add order warehouse"
            @close="close_modal"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <form v-on:submit.prevent="add_warehouse" id="add_warehouse">
                <input type="text" class="form-control" v-model="data.name" name="Name" placeholder="Name" title="Name" required>
                <input type="url" class="form-control" v-model="data.url" name="url" placeholder="url" title="url" required>

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
                    form="add_warehouse"
                    :class="{'btn btn-primary': true}"
                >
                Add warehouse
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
                is_warehouse_edit_model: false,
                is_warehouse_add_model: false,

                regions: [],
                data: {
                    name: '',
                    url: '',
                    published: 0,
                    article_id: 0
                }
            }
        },
        methods: {
            add_warehouse(){
                    axios
                    .post('/warehouse/add_warehouse/', {
                        data: this.data,

                        _method: 'Post'
                    })
                    .then(Response => {
                        alert('Added successfully')
                        this.$emit('update')
                        this.is_warehouse_add_model = false
                        this.clear_form()
                    })
                    .catch(error => console.log(error))
            },
            clear_form(){
                this.data = {
                    name: '',
                    url: '',
                    published: 0,
                    article_id: 0
                }
            },
            show_modal(){
                this.is_warehouse_add_model = true
            },
            close_modal(){
                if(confirm('Are you sure, you want close form? All data whil deleted!')){
                    this.is_warehouse_add_model = false
                    this.clear_form()
                }
            }
        }
    }
</script>

<style>

</style>