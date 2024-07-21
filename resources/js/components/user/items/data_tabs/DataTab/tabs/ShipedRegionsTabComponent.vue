<template>
    <tr>
        <td :class='"text-center"'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>
        <td>{{table_info.region}} </td>
        <td>|</td>
        <td :class='"text-center"'>{{table_info.shiping_price}}</td>
        <td>|</td>
        <td>
            <button class="btn btn-primary" type="submit" @click="open_editing_modal(table_info)" v-if="$can('edit', 'shiping_region')"><i class="fa fa-pencil" aria-hidden="true"></i></button>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_region(table_info.id)"  v-if="$can('del', 'shiping_region')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>

        <shipedRegionEditModal ref="shipedRegionEditModal" @restart="update"/>
    </tr>
</template>

<script>
    import shipedRegionEditModal from "../tab_modals/edit/EditShipingRegionModalComponent.vue";

    export default {
        components: {
            shipedRegionEditModal,
        },
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        data(){
            return{
                // is_order_region_edit_model: false,
            }
        },
        methods: {
            del_region(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/shiped_region/del_region/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.update()
                    })
                    .catch(error => console.log(error))
                }
            },
            update(){
                this.$emit('restart')
            },
            open_editing_modal(actyve_info){
                this.$refs.shipedRegionEditModal.open_editing_modal(actyve_info)
            },
        }
    }
</script>

<style>

</style>