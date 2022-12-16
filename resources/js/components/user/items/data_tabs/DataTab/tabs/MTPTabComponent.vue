<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>
        
        <td @click="show_mtp_model(table_info.id)" class="cursor_zoom_in">{{table_info.name}} </td>
        
        <td>|</td>
        <td>
            <router-link class="btn btn-primary" :to="{ name: 'MTPEdit', params: { id: table_info.id } }" >Edit</router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_multi_pitch(table_info.id)">Delete</button>
        </td>

        <mtpModal
            ref="mtp_modal"
        />
    </tr>
</template>

<script>
    import mtpModal from "../tab_modals/MTPModalComponent.vue";
    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
            mtpModal
        },
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            del_multi_pitch(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/mtp/del_mtp/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            show_mtp_model(sector_id){
                this.$refs.mtp_modal.show_mtp_modal(sector_id)
            },
        }
    }
</script>

<style>

</style>