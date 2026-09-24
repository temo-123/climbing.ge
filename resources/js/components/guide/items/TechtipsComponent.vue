<template>
    <div class="h-recent-work services" id="services">
        <div class="container" v-if="techtips.length > 0">

            <h2 class='index_h2'>{{ $t('guide.title.tech tips') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
            <h3 class='article_list_short_description'> {{this.$siteData.data.tech_tips_description}} </h3>
                    
            <card-slider :items="techtips" item-key="global_data.id" :desktop="3" :mobile="1" :autoplay="5000" slide-class="tip-item">
                <template #default="{ item: tip }">
                    <div class="product-image" v-if="tip.global_data.new_flag">
                        <div class="discount-percent-badge discount_percent_badge_for_techtip discount-badge-fourty">NEW</div>
                    </div>
                    <div class="thumbnail">
                        <router-link :to="'tech_tip/'+tip.global_data.url_title" class="info">
                            <site-img v-if="tip.global_data.image != null" :src="'/public/images/tech_tip_img/'+tip.global_data.image" :img_class="'img-responsive'" :alt='tip.locale_data.title'/>
                            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='tip.locale_data.title'/>
                        </router-link>
                        <div class="caption">
                            <router-link :to="'tech_tip/'+tip.global_data.url_title" class="info">
                                <h3 class="text-center text-uppercase tip_title">{{ tip.locale_data.title }}</h3>
                            </router-link>
                            <!-- <hr> -->
                            <span class="text-center" v-html="tip.locale_data.short_description"></span>
                        </div>
                    </div>
                </template>
            </card-slider>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                techtips: [],
                // tip_num: 0
            }
        },
        mounted() {
            this.get_techtips()
        },
        methods: {
            get_techtips(){
                axios
                .get('/get_article/get_locale_articles/tech_tip/'+localStorage.getItem('lang'))
                .then(response => {
                    // this.techtips = response.data
                    this.techtips = response.data
                })
                .catch(error =>{
                })
            },
        }
    };
</script>

<style scoped>
.tip_title{
    margin: 10px;
    color: #2d2c2c;
    transition: 0.5s;
}
.tip_title:hover{
    color: #7c7cfd;
    transition: 0.5s;
}

@media (max-width: 990px){
    .thumbnail{
        /* height: 18em;
        max-height: 22em; */
        margin-top: 20px;
    }
    .thumbnail p{
        font-size: 80%;
    }
}

@media (max-width: 375px){
    .thumbnail{
        /* height: 9em;
        max-height: 22em; */
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
/* .caption h3{
    margin: 0;
} */
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
.thumbnail {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.thumbnail:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

.discount_percent_badge_for_techtip {
    margin: 5px 20px 0 0;
}
</style>