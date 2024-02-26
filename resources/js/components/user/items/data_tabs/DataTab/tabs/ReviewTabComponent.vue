<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.review.id}}</td>

        <td>|</td>
        <td>{{table_info.route.name}}</td>

        <td>|</td>
        <td>
            <starsReiting 
                :reviews_count_prop = 1
                :reviews_stars_prop = table_info.review.stars
                :rewiew_count_text_prop = false
            />
        </td>

        <td>|</td>
        <td>
            <button type="submit" class="btn btn-primary" @click="edit_review_modal(table_info.review.id)" >
                <i class="fa fa-pencil" aria-hidden="true"></i>
            </button>
        </td>
        
        <!-- <td v-if="table_name == 'Climbing routes review'">|</td> -->
        <td>|</td>
        
        <td>
            <button type="submit" class="btn btn-danger" @click="del_review(table_info.review.id)" ><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>

        <reviewEditModal ref="review_edit_modal" @restart="reset"/>
    </tr>
</template>

<script>
    import starsReiting from '../../../../../global_components/StarReitingShowComponent.vue'
    import reviewEditModal from '../../../modal/review/ReviewEditModal.vue'
    export default {
        components: {
            starsReiting,
            reviewEditModal,
        },
        props: [
            'table_info',
            'table_name',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            del_review(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/route_review/del_route_review/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            edit_review_modal(id){
                this.$refs.review_edit_modal.show_modal(id)
            }
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