<template>
    <div>
        <div class="row" v-if="route.reviews_count > 0">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="ratings">                                        
                        <i class="fa fa-star rating-color" v-for="i in stars.whole_stars" :key="i"></i>
                        <i class="fa fa-star-half-o rating-color" v-if="stars.part_stars != 0"></i>
                        <i class="fa fa-star" v-for="i in stars.other_stars" :key="i"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h5 class="review-count">{{ route.reviews_count }} Reviews ({{ route.reviews_stars }} Stars)</h5>
            </div>
        </div>
    </div>
</template>

<script>

export default {
        data () {
            return {
                stars: {
                    whole_stars: 3,
                    part_stars: 0.5,
                    other_stars: 1
                },
                route: {
                    reviews_count: 4,
                    reviews_stars: 3.5,
                }
            }
        }
}
</script>


<style>
.card{
    width:380px;
    border:none;
    height:280px;
}

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