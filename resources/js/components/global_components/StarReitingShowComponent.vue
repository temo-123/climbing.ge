<template>
   <div class="row" v-if="reviews_count_prop > 0">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="ratings">                                        
                    <i class="fa fa-star rating-color" v-for="(i, index) in stars.whole_stars" :key="index"></i>
                    <i class="fa fa-star-half-o rating-color" v-if="stars.part_stars != 0"></i>
                    <i class="fa fa-star" v-for="(i, index) in stars.other_stars" :key="index"></i>
                </div>
            </div>
        </div>
        <div class="col-md-12" v-if="rewiew_count_text_prop">
            <h5 class="review-count">{{ reviews_count_prop }} Reviews ({{ reviews_stars_prop }} Stars)</h5>
        </div>
    </div>
</template>


<script>
export default {
    components: { 
        //
    },
    props: {
        reviews_count_prop: {
            type: [String, Number],
        },
        reviews_stars_prop: {
            type: [String, Number],
        },
        rewiew_count_text_prop: {
            type: Boolean,
            default: true
        }
    },

    data: function () {
        return {
            stars: {
                whole_stars: 0,
                part_stars: 0,
                other_stars: 0,
            },

            // data: {
            //     reviews_count: 0,
            //     reviews_stars: 0,
            //     rewiew_count_text: true
            // },
        };
    },
    watch: {
        // '$route' (to, from) {
            // this.update()
        // },
        reviews_stars_prop: function(){
            this.colculate_stars()
        },
    },
    mounted() {
        // this.update()
        this.colculate_stars()
    },
    methods: {
        // update(){
        //     this.data = {
        //         reviews_count: this.reviews_count_prop,
        //         reviews_stars: this.reviews_stars_prop,
        //         rewiew_count_text: this.rewiew_count_text_prop
        //     },
        //     this.colculate_stars()
        // },
        colculate_stars(){
            if(Number(this.reviews_stars_prop) % 1 == 0){
                this.stars.part_stars = 0 // get number after comma
                this.stars.whole_stars = Math.floor(this.reviews_stars_prop) // get number befor comma
            }
            else{
                this.stars.part_stars = Number((this.reviews_stars_prop+' ').split(".")[1].substr(0,1)); // get number after comma
                this.stars.whole_stars = Math.floor(this.reviews_stars_prop) // get number befor comma
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
</style>