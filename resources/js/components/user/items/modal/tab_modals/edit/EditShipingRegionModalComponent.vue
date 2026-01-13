<template>
    <stack-modal
                :show="is_order_region_edit_model"
                title="Edit order region"
                @close="close_modal"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <h1>Edit shiped region</h1>
                <input type="text" class="form-control" v-model="editing_data.region" name="region name" placeholder="region" title="region">
                <input type="number" class="form-control" v-model="editing_data.shiping_price" name="shiping price" placeholder="Shiping price" title="Shiping price">
                <input type="number" class="form-control" v-model="editing_data.free_shiping_price_after" name="free shiping price after" placeholder="Free shiping price after" title="Free shiping price after">
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_region()"
                    >
                    Edit region
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
            // 'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        data(){
            return{
                is_order_region_edit_model: false,

                editing_data: {
                    id: '',
                    region: '',
                    shiping_price: '',
                    free_shiping_price_after: '',
                },
            }
        },
        methods: {
            show_modal(editing_info = null){
                if (editing_info) {
                    this.editing_data.id = editing_info.id || ''
                    this.editing_data.region = editing_info.region || ''
                    this.editing_data.shiping_price = editing_info.shiping_price || ''
                    this.editing_data.free_shiping_price_after = editing_info.free_shiping_price_after || ''
                }
                this.is_order_region_edit_model = true
            },
            close_modal(){
                this.editing_data.id = ''
                this.editing_data.region = ''
                this.editing_data.shiping_price = ''
                this.editing_data.free_shiping_price_after = ''
                this.is_order_region_edit_model = false
            },
            edit_region(){
                axios
                .post('/set_shiped_region/edit_region/'+this.editing_data.id, {
                    editing_data: this.editing_data,
                })
                .then(Response => {
                    this.close_modal()

                    this.$emit('update')
                })
                .catch(error => console.log(error))
            },
        }
    }
</script>

<style>

</style>