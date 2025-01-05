<template>
    <stack-modal
            :show="is_order_region_add_model"
            title="Add order region"
            @close="close_modal"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <h1>Add shiped region</h1>
            <form id="add_region" v-on:submit.prevent="add_region" >
                <input type="text" class="form-control" v-model="adding_data.region" name="region name" placeholder="region" title="region" required>
                <input type="number" class="form-control" v-model="adding_data.shiping_price" name="shiping price" placeholder="Shiping price" title="Shiping price" required>

                <input type="number" class="form-control" v-model="adding_data.free_shiping_price_after" name="free shiping price after" placeholder="Free shiping price after" title="Free shiping price after">
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="add_region"
                    :class="{'btn btn-primary': true}"
                >
                Add region
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
        mountid(){
            // console.log(this.table_info)
        },
        data(){
            return{
                is_order_region_edit_model: false,
                is_order_region_add_model: false,

                adding_data: {
                    region: '',
                    shiping_price: '',
                    free_shiping_price_after: '',
                }
            }
        },
        methods: {
            add_region(){
                    axios
                    .post('/shiped_region/add_region/', {
                        adding_data: this.adding_data
                    })
                    .then(Response => {
                        alert('region added socsesfuly')
                        this.$emit('restart')
                        this.is_order_region_add_model = false
                    })
                    .catch(error => console.log(error))
            },
            show_modal(){
                this.is_order_region_add_model = true
            },
            close_modal(){
                if(confirm('Are you sure, you want close form? All data whil deleted!')){
                    this.is_order_region_add_model = false

                    this.adding_data = {
                        region: '',
                        shiping_price: '',
                        free_shiping_price_after: '',
                    }
                }
            }
        }
    }
</script>

<style>

</style>