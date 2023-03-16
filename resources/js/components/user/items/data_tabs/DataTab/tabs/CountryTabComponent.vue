<template>
    <tr>
        <td :class='"text-center"'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>
        <td>{{table_info.country}} </td>
        <td>|</td>
        <td :class='"text-center"'>{{table_info.shiping_price}}</td>
        <td>|</td>
        <td>
            <button class="btn btn-primary" type="submit" @click="open_editing_modal(table_info)" v-if="$can('edit', 'shiping_country')"><i class="fa fa-pencil" aria-hidden="true"></i></button>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_country(table_info.id)"  v-if="$can('del', 'shiping_country')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>

        <stack-modal
                :show="is_order_country_edit_model"
                title="Edit order country"
                @close="is_order_country_edit_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <h1>Edit shiped country</h1>
                <input type="text" class="form-control" v-model="editing_data.country" name="country name" placeholder="Country" title="Country">
                <input type="text" class="form-control" v-model="editing_data.shiping_price" name="shiping price" placeholder="Shiping price" title="Shiping price">
                <input type="text" class="form-control" v-model="editing_data.free_shiping_price_after" name="free shiping price after" placeholder="Free shiping price after" title="Free shiping price after">
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_country(table_info.id)"
                    >
                    Edit country
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_order_country_add_model"
                title="Add order country"
                @close="is_order_country_add_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <h1>Add shiped country</h1>
                <input type="text" class="form-control" v-model="adding_data.country" name="country name" placeholder="Country" title="Country">
                <input type="text" class="form-control" v-model="adding_data.shiping_price" name="shiping price" placeholder="Shiping price" title="Shiping price">
                <input type="text" class="form-control" v-model="adding_data.free_shiping_price_after" name="free shiping price after" placeholder="Free shiping price after" title="Free shiping price after">
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="add_country()"
                    >
                    Add country
                    </button>
                </div>
            </div>
        </stack-modal>
    </tr>
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
            del_country(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/del_country/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            open_editing_modal(editing_info){
                this.editing_data.country = editing_info.country
                this.editing_data.shiping_price = editing_info.shiping_price
                this.editing_data.free_shiping_price_after = editing_info.free_shiping_price_after

                this.is_order_country_edit_model = true
            },
            edit_country(id){
                    axios
                    .post('../../api/edit_country/'+id, {
                        editing_data: this.editing_data,

                        _method: 'Post'
                    })
                    .then(Response => {
                        this.is_order_country_edit_model = false
                        alert('Editing socsesful')
                    })
                    .catch(error => console.log(error))
            },
            add_country(){
                    axios
                    .post('../../api/add_country/', {
                        adding_data: this.adding_data,

                        _method: 'Post'
                    })
                    .then(Response => {
                        alert('Editing socsesful')
                        this.is_order_country_add_model = false
                    })
                    .catch(error => console.log(error))
            },
            add_country_model_open(){
                this.is_order_country_add_model = true
            }
        }
    }
</script>

<style>

</style>