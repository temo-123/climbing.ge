<template>
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
</template>


<script>
export default {
    components: { 
        //
    },
    props: [
        // "sector",
    ],
    data: function () {
        return {
            stars: {
                whole_stars: 0,
                part_stars: 0,
                other_stars: 0,
            },

            route: {
                reviews_count: 0,
                reviews_stars: 0,
            }
        };
    },
    mounted() {
        //
    },
    methods: {
        colculate_stars(stars, count){
            this.route.reviews_count = count
            this.route.reviews_stars = stars

            if(stars % 1 == 0){
                this.stars.part_stars = 0 // get number after comma
                this.stars.whole_stars = Math.floor(stars) // get number befor comma
            }
            else{
                this.stars.part_stars = Number((stars+' ').split(".")[1].substr(0,1)); // get number after comma
                this.stars.whole_stars = Math.floor(stars) // get number befor comma
            }

            // colculate empty srats
            if(this.stars.part_stars > 0){
                this.stars.other_stars = 4 - this.stars.whole_stars
            }
            else{
                this.stars.other_stars = 5 - this.stars.whole_stars
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

/* .rating-header {
    margin-top: -10px;
    margin-bottom: 10px;
}
.star_review_button{
    border: none;
    background: none;
    cursor: pointer;
    margin: 0;
    padding: 0;
    width: 19%;

    color: #51acd7;
    display: inline-block;
    font-weight: 500;
    height: 36px;
    line-height: 33px;
    text-transform: uppercase;
    transition: background 0.2s ease 0s, opacity 0.2s ease 0s;
}
.star_review_button i{
    font-size: 45px;
}
.actyve_star {
    color: rgb(245, 115, 115) !important;
}

.star_review_button:hover{
    background-color: #fff;
    color: rgb(245, 115, 115) !important;
}
.star_review_button :active {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}

.st_1_actyve {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}
.st_2_actyve {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}
.st_3_actyve {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}
.st_4_actyve {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
}
.st_5_actyve {     
    background-color:rgb(255, 255, 255); 
    color: rgb(245, 115, 115) !important;   
} */
</style>