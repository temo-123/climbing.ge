<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>

        <td>
            <a @click="show_sector_model(table_info.id)" class="sector_model_action_button">{{table_info.name}}</a>
        </td>
        <td>|</td>


        <td v-if="table_info.published === null">0</td>
        <td v-else>{{table_info.published}}</td>

        <td>|</td>

        <td >
            <router-link class="btn btn-primary" :to="{ name: 'sectorEdit', params: { id: table_info.id } }" >Edit</router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_sector(table_info.id)">Delete</button>
        </td>
    </tr>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
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
                    .post('../../api/sector/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.update(this.tab_num)
                    })
                    .catch(error => console.log(error))
                }
            },
            show_sector_model(sector_id){
                // console.log(sector_id)
                this.$emit('sector_id', sector_id)
                // this.$emit('show_sector_modal', true)
            },
        }
    }
</script>

<style>
.sector_model_action_button{
    cursor: pointer;
}
</style>