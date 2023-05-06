<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>

        <td>|</td>

        <td>
            {{table_info.name}}
        </td>

        <td>|</td>
        <td>
            <router-link class="btn btn-primary" :to="{ name: 'MTPPitchEdit', params: { id: table_info.id } }" v-if="$can('edit', 'mtp_pitch')"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_pitch(table_info.id)" v-if="$can('del', 'mtp_pitch')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
    </tr>
</template>

<script>
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
            del_pitch(pitch_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/mtp/mtp_pitch/del_pitch/'+pitch_id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>