<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>

        <td>|</td>
        <td v-if="table_name == 'My climbing routes review' || table_name == 'Climbing routes review'"> climbing route</td>
        <td v-if="table_name == 'My products review' || table_name == 'Products review'">product</td>

        <td>|</td>
        <td>
            <div class="ratings">                                        
                <i class="fa fa-star rating-color"></i>
                <i class="fa fa-star rating-color"></i>
                <i class="fa fa-star rating-color"></i>
                <i class="fa fa-star rating-color"></i>
                
                <i class="fa fa-star"></i>
            </div>
        </td>


        <td v-if="table_name == 'My climbing routes review' || table_name == 'My products review'">|</td>
        <td v-if="table_name == 'My climbing routes review' || table_name == 'My products review'">
            <button type="submit" class="btn btn-primary" @click="del_route(table_info.id)" >
                <i class="fa fa-pencil" aria-hidden="true"></i>
            </button>
        </td>
        
        <td>|</td>
        
        <td>
            <button type="submit" class="btn btn-danger" @click="del_route(table_info.id)" ><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
    </tr>
</template>

<script>
    export default {
        props: [
            'table_info',
            'table_name',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            del_route(id){
                if(confirm('Are you sure, you want delite it?')){
                    // axios
                    // .post('../../api/route/'+id, {
                    //     id: id,
                    //     _method: 'DELETE'
                    // })
                    // .then(Response => {
                    //     // this.update(this.tab_num)

                    //     this.$emit('restart')
                    // })
                    // .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>
.ratings{
    margin-right:10px;
}

.ratings i{
    
    color:#cecece;
    font-size:32px;
}

.rating-color{
    color:#fbc634 !important;
}

.review-count{
    font-weight:400;
    margin-bottom:2px;
    font-size:24px !important;
}

.small-ratings i{
  color:#cecece;   
}

.review-stat{
    font-weight:300;
    font-size:18px;
    margin-bottom:2px;
}
</style>