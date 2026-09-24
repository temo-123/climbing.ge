<template>
    <div class="partners" v-if="partners.length > 0">
        <div class="container">

            <h2 class='index_h2'>{{ $t('guide.title.partner') }}</h2>

            <div class="bar"><i class="fa fa-handshake-o"></i></div>

            <h3 class="article_list_short_description"> <span v-html="$siteData.data.partners_description"></span> </h3>

            <card-slider
                :items="partners"
                item-key="global_data.id"
                :desktop="4"
                :tablet="2"
                :mobile="1"
                :mobile-breakpoint="480"
                :tablet-breakpoint="768"
                :autoplay="5000"
                loop
                slide-class="partner-item"
            >
                <template #default="{ item: partner }">
                    <div class="thumbnail partner_thumbnail">
                        <site-img v-if="partner.global_data.image != null" :src="'/public/images/partner_img/'+partner.global_data.image" :img_class="'img-responsive'" :alt='partner.locale_data.title'/>
                        <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='partner.locale_data.title'/>
                        <div class="caption">
                            <h3 class="text-center text-uppercase partner_title">{{ partner.locale_data.title }}</h3>
                            <span class="text-center" v-html="partner.locale_data.short_description"></span>
                        </div>
                    </div>
                </template>
            </card-slider>
        </div>
    </div>
</template>

<script>
    import axios_mixin from '../../../mixins/axios_mixin'

    export default {
        mixins: [
            axios_mixin
        ],
        props: [
            // 'partners',
        ],
        data: function () {
            return {
                partners: []
            };
        },
        mounted() {
            this.get_partners()
        },
        methods: {
            get_partners(){
                this.get_articles('partners', localStorage.getItem('lang'),
                    (data) => {
                        this.partners = data;
                    },
                    (error) => {
                        console.error('Error fetching articles:', error);
                    },
                    () => {
                        this.indoor_article_loading = false;
                    }
                );
            },
        }
    };
</script>

<style scoped>
    .partners {
        margin-left: 5%;
        margin-right: 5%;
    }

    /* slide wrapper is rendered by card-slider, so reach it through :deep */
    :deep(.partner-item) {
        text-align: center;
    }
    @media (hover: hover) {
        .partner_thumbnail {
            transition: transform 0.2s;
        }
        .partner_thumbnail:hover {
            transform: scale(1.05);
        }
    }
</style>

<!-- <style>
.partners{
    margin-left: 5%;
    margin-Right: 5%;
}
.previes_partner_bottom{
    float: left;
}
.next_partner_bottom{
    float: right;
}
.next_partner_bottom, .previes_partner_bottom{
    margin-top: -10%;
    font-size: 150%;
    cursor: pointer;
}
</style> -->