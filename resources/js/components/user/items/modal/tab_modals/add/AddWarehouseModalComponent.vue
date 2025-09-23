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

                <div class="form-group">
                    <input type="checkbox" id="general" name="general" v-model="data.general">
                    <label for="general">Is this warehouse is general?</label>
                </div>
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
                is_warehouse_add_model: false,

                regions: [],
                data: {
                    name: '',
                    general: false,
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
                        // alert('Added successfully')
                        this.$emit('update')
                        this.is_warehouse_add_model = false
                        this.clear_form()
                    })
                    .catch(error => console.log(error))
            },
            clear_form(){
                this.data = {
                    name: '',
                    general: false,
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