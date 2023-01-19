<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>

        <td @click="show_sector_model(table_info.id)" class="cursor_zoom_in">
            <a>{{table_info.name}}</a>
        </td>
        <td>|</td>


        <td v-if="table_info.published === null">0</td>
        <td v-else>{{table_info.published}}</td>

        <td>|</td>

        <td >
            <router-link class="btn btn-primary" :to="{ name: 'sectorEdit', params: { id: table_info.id } }" ><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_sector(table_info.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>

        <sectorModal
            ref="sector_modal"
        />
    </tr>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import sectorModal from "../tab_modals/SectorModalComponent.vue";
    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,

            sectorModal
        },
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            del_sector(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/sector/del_sector_sector/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            show_sector_model(sector_id){
                this.$refs.sector_modal.show_sector_modal(sector_id)
            },
        }
    }
</script>

<style>
.sector_model_action_button{
    cursor: pointer;
}
</style>