<template>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btnrating btn-lg star_review_button" @click="actyvate_star(1)" data-attr="1" id="rating-star-1" @mouseover="actyvate_temperary_actyve_star(1)" @mouseleave="disactyvate_temperary_actyve_star()">
                <i class="fa fa-star actyve_star" v-if="stars_arr.st_1" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true" v-else></i>
            </button>
            <button type="button" class="btnrating btn-lg star_review_button" @click="actyvate_star(2)" data-attr="2" id="rating-star-2" @mouseover="actyvate_temperary_actyve_star(2)" @mouseleave="disactyvate_temperary_actyve_star()">
                <i class="fa fa-star actyve_star" 
                    v-if="stars_arr.st_1 && stars_arr.st_2" 
                aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true" v-else></i>
            </button>
            <button type="button" class="btnrating btn-lg star_review_button" @click="actyvate_star(3)" data-attr="3" id="rating-star-3" @mouseover="actyvate_temperary_actyve_star(3)" @mouseleave="disactyvate_temperary_actyve_star()">
                <i class="fa fa-star actyve_star" 
                    v-if="stars_arr.st_1 && stars_arr.st_2 && stars_arr.st_3" 
                aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true" v-else></i>
            </button>
            <button type="button" class="btnrating btn-lg star_review_button" @click="actyvate_star(4)" data-attr="4" id="rating-star-4" @mouseover="actyvate_temperary_actyve_star(4)" @mouseleave="disactyvate_temperary_actyve_star()">
                <i class="fa fa-star actyve_star" 
                    v-if="stars_arr.st_1 && stars_arr.st_2 && stars_arr.st_3 && stars_arr.st_4" 
                aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true" v-else></i>
            </button>
            <button type="button" class="btnrating btn-lg star_review_button" @click="actyvate_star(5)" data-attr="5" id="rating-star-5" @mouseover="actyvate_temperary_actyve_star(5)" @mouseleave="disactyvate_temperary_actyve_star()">
                <i class="fa fa-star actyve_star" 
                    v-if="stars_arr.st_1 && stars_arr.st_2 && stars_arr.st_3 && stars_arr.st_4 && stars_arr.st_5" 
                aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true" v-else></i>
            </button>
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
            stars_arr: {
                st_1: false,
                st_2: false,
                st_3: false,
                st_4: false,
                st_5: false,
            },
            stars: 0,
        };
    },
    mounted() {
        //
    },
    methods: {
        actyvate_star(st_num){
            this.stars = st_num

            for (let i = 1; i < st_num + 1; i++) {
                let actyve_prefix = "st_" + i
                this.stars_arr[actyve_prefix] = true
            }
            for (let j = st_num + 1; j < 5 + 1; j++) {
                let actyve_prefix = "st_" + j
                this.stars_arr[actyve_prefix] = false
            }

            this.$emit('get_stars', st_num)
        },

        actyvate_temperary_actyve_star(st_num){
            for (let i = 1; i < st_num + 1; i++) {
                let actyve_prefix = "st_" + i
                this.stars_arr[actyve_prefix] = true
            }
        },

        disactyvate_temperary_actyve_star(){
            if(this.stars == 0){
                for (let j = 1; j < 5; j++) {
                    let actyve_prefix = "st_" + j
                    this.stars_arr[actyve_prefix] = false
                }
            }
        },
    }
}
</script>

<style>
.rating-header {
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
}
</style>