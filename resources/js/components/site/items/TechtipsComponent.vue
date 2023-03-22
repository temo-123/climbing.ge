<template>
    <div class="h-recent-work services" id="services">
        <div class="container" v-if="techtips.length > 0">

            <h2 class='index_h2'>{{ $t('guide.title.tech tips') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
            <h3> <span v-html="this.$siteData.tech_tips_description"></span> </h3>
                    
            <div class="container">
                <div class="row">
                    
                    <!-- <div class="tips_list">
                        <div class="tips"> -->

                            <div class="col-xs-6 col-sm-6 col-md-3" v-for="tip in techtips" :key="tip.id">
                                <div class="product-image" v-if="tip.new_flag">
                                    <div class="discount-percent-badge discount_percent_badge_for_techtip discount-badge-fourty">NEW</div>
                                </div>
                                <!-- {{ tip_num+=1 }} -->
                                <div class="thumbnail">
                                    <!-- <site-img :src="'./public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='tip[0][0].title'/> -->
                                    <router-link :to="'tech_tip/'+tip.url_title" class="info">
                                        <site-img v-if="tip.image != null" :src="'/public/images/tech_tip_img/'+tip.image" :img_class="'img-responsive'" :alt='tip[0][0].title'/>
                                        <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='tip[0][0].title'/>
                                    </router-link>
                                    <div class="caption">
                                        <router-link :to="'tech_tip/'+tip.url_title" class="info">
                                            <h3>{{ tip[0][0].title }}</h3>
                                        </router-link>
                                        <!-- <p>{{ tip[0][0].short_description }}</p> -->
                                        <hr>
                                        <span class="text-center" v-html="tip[0][0].short_description"></span>
                                    </div>
                                </div>
                                
                                <!-- <div v-if="tip_num % 2 == 0" class="clearfix visible-xs-block"></div> -->
                            </div>


                        <!-- </div>
                    </div> -->

                    <div class="previes_tip_bottom" v-if="techtips.length > 4">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    </div>

                    <div class="next_tip_bottom" v-if="techtips.length > 4">
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                techtips: [],
                slider_index: 0,
                // tip_num: 0
            }
        },
        mounted() { 
            this.get_techtips()
        },
        methods: {
            get_techtips(){
                axios
                .get('../api/articles/tech_tip/'+localStorage.getItem('lang'))
                .then(response => {
                    // this.techtips = response.data
                    this.techtips = response.data.slice(0, 4)
                })
                .catch(error =>{
                })
            },

            next_tips(){
                var test_num = 0
                test_num = this.image_num
                this.test_num += 1
                if(test_num < (this.image_length - 1)){
                    this.image_num += 1
                }
                if(test_num < 4){
                    this.image_num -= 1
                }
            },

            prewies_tips(){
                var test_num = 0
                test_num = this.image_num
                this.test_num -= 1
                if(test_num > 0){
                    this.image_num -= 1
                }
                if(test_num < 4){
                    this.image_num -= 1
                }
            },
        }
    };
</script>

<style scoped>


@media (max-width: 990px){
    .thumbnail{
        height: 18em;
        max-height: 22em;
        margin-top: 20px;
    }
    .thumbnail p{
        font-size: 80%;
    }
}

@media (max-width: 375px){
    .thumbnail{
        height: 9em;
        max-height: 22em;
        margin-top: 16px;
    }
    .thumbnail p{
        font-size: 80%;
    }
}
.tip{
    width: 25%;
    height: auto;
    margin: 2%;
}
.caption h3{
    margin: 0;
}
.caption p{
    text-align: center;
}
/* .tips_list{
    max-height: 100%; 
    overflow: hidden;
}
.tips{
    display: flex;
} */
.previes_tip_bottom{
    float: left;
}
.next_tip_bottom{
    float: right;
}
.next_tip_bottom, .previes_tip_bottom{
    margin-top: -12%;
    font-size: 150%;
    cursor: pointer;
}

.discount_percent_badge_for_techtip {
    margin: 5px 20px 0 0;
}
</style>