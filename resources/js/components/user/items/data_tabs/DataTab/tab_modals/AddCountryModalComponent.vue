<template>
    <stack-modal
            :show="is_order_country_add_model"
            title="Add order country"
            @close="close_modal"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <h1>Add shiped country</h1>
            <form id="add_country" v-on:submit.prevent="add_sale_code" >
                <input type="text" class="form-control" v-model="adding_data.country" name="country name" placeholder="Country" title="Country" required>
                <input type="text" class="form-control" v-model="adding_data.shiping_price" name="shiping price" placeholder="Shiping price" title="Shiping price" required>
                <input type="text" class="form-control" v-model="adding_data.free_shiping_price_after" name="free shiping price after" placeholder="Free shiping price after" title="Free shiping price after">
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="add_country"
                    :class="{'btn btn-primary': true}"
                >
                Add country
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
                is_order_country_edit_model: false,
                is_order_country_add_model: false,

                editing_data: {
                    country: '',
                    shiping_price: '',
                    free_shiping_price_after: '',
                },

                adding_data: {
                    country: '',
                    shiping_price: '',
                    free_shiping_price_after: '',
                }
            }
        },
        methods: {
            // del_country(id){
            //     if(confirm('Are you sure, you want delite it?')){
            //         axios
            //         .post('../../api/del_country/'+id, {
            //             id: id,
            //             _method: 'DELETE'
            //         })
            //         .then(Response => {
            //             this.$emit('restart')
            //         })
            //         .catch(error => console.log(error))
            //     }
            // },
            add_country(){
                    axios
                    .post('../../api/add_country/', {
                        adding_data: this.adding_data,

                        _method: 'Post'
                    })
                    .then(Response => {
                        alert('Country added socsesfuly')
                        this.$emit('restart')
                        this.is_order_country_add_model = false
                    })
                    .catch(error => console.log(error))
            },
            add_country_model_open(){
                this.is_order_country_add_model = true
            },
            close_modal(){
                if(confirm('Are you sure, you want close form? All data whil deleted!')){
                    this.is_order_country_add_model = false
                }
            }
        }
    }
</script>

<style>

</style>