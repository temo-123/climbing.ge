<template>
    <div>
        <span v-html="this.article_prop.locale_data.text" id="description" v-if="this.article_prop.locale_data.text != NUll"></span>

        <h3 class="navbar_title display-smaller-then-768px" v-if="local_businesses.length != 0">{{ $t('guide.article_right_nabar.recomended_services') }}</h3>

        <div class="row local_bisnes display-smaller-then-768px" v-if="local_businesses.length != 0">
            <div class="col-xs-6 col-sm-6 col-md-6" v-for="bisnes in local_businesses" :kay="bisnes.global_data.id">
                <div class="thumbnail">
                    <router-link v-if="bisnes.image.length != 0" style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                        <img :src="'../../../images/suport_local_bisnes_img/' + bisnes.image" :alt="bisnes.local_data.title">
                    </router-link>
                    <router-link v-else style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                        <img :src="'/../public/images/site_img/image.png'" :alt="bisnes.local_data.title">
                    </router-link>
                    <div class="caption">
                        <router-link style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                            <h3>{{ bisnes.local_data.title }}</h3>
                        </router-link>
                    </div>
                    <div class="caption">
                        <span v-html="bisnes.local_data.short_description"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best time for climbing block -->
        <div v-if="this.article_prop.global_data.weather || this.article_prop.general_info.best_time.length != 0">
            <div v-if="(this.article_prop.global_data.weather == NULL && this.article_prop.locale_data.best_time != NULL)">
                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                <generalInfo :global_info_prop="article_prop.general_info.best_time" :locale_data_prop="article_prop.locale_data.best_time"/>
            </div>  
            <div v-else-if="this.article_prop.global_data.weather != NULL && this.article_prop.locale_data.best_time != NULL">
                <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                <div class="row">
                    <generalInfo :global_info_prop="article_prop.general_info.best_time" :locale_data_prop="article_prop.locale_data.best_time"/>

                    <div class="col-md-6" style="text-align: center;">
                        <span v-html="this.article_prop.global_data.weather"></span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="this.article_prop.global_data.weather || this.article_prop.general_info.best_time.length != 0 || this.article_prop.global_data.weather != null">
            <span v-html="this.article_prop.global_data.weather"></span>
        </div>

        <!-- addres -->
        <div v-if="this.article_prop.locale_data.address != NUll">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.address')}}</h2>
            <span v-html="this.article_prop.locale_data.address"></span>
        </div>
        
        <!-- how get -->
        <h2 v-if="(this.article_prop.locale_data.how_get != NUll && this.article_prop.locale_data.how_get != '') || (this.article_prop.global_data.map != NULL && this.article_prop.global_data.map != '')" id="how_to_get_there">{{ $t('guide.article.title.how get')}}</h2>

        <div v-if="this.article_prop.locale_data.how_get != NUll && this.article_prop.locale_data.how_get != ''">
            <!-- <h2 id="how_to_get_there">{{ $t('guide.article.title.how get')}}</h2> -->
            <span v-html="this.article_prop.locale_data.how_get"></span>
        </div>

        <!-- map -->
        <div v-if="this.article_prop.global_data.map != NULL">
            <div class="article_map">
                <span v-html="this.article_prop.global_data.map"></span>
            </div>
        </div>

        <!-- price -->
        <div v-if="this.article_prop.locale_data.prices_text != NUll">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.price')}}</h2>
            <span v-html="this.article_prop.locale_data.prices_text"></span>
        </div>

        <!-- what need -->
        <div v-if="this.article_prop.locale_data.what_need || this.article_prop.general_info.what_need_info.length != 0">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.what need')}}</h2>

            <generalInfo :global_info_prop="article_prop.general_info.what_need_info" :locale_data_prop="article_prop.locale_data.what_need"/>

        </div>

        <!-- info -->
        <div v-if="this.article_prop.locale_data.info || this.article_prop.general_info.info_block.length != 0">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.info')}}</h2>

            <generalInfo :global_info_prop="article_prop.general_info.info_block" :locale_data_prop="article_prop.locale_data.info"/>

        </div>

        <h3 class="navbar_title display-smaller-then-768px" v-if="local_businesses.length != 0">{{ $t('guide.article_right_nabar.recomended_services') }}</h3>

        <div class="row local_bisnes display-smaller-then-768px" v-if="local_businesses.length != 0">
            <div class="col-xs-6 col-sm-6 col-md-6" v-for="bisnes in local_businesses" :kay="bisnes.global_data.id">
                <div class="thumbnail">
                    <router-link v-if="bisnes.image.length != 0" style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                        <img :src="'../../../images/suport_local_bisnes_img/' + bisnes.image" :alt="bisnes.local_data.title">
                    </router-link>
                    <router-link v-else style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                        <img :src="'/../public/images/site_img/image.png'" :alt="bisnes.local_data.title">
                    </router-link>
                    <div class="caption">
                        <router-link style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                            <h3>{{ bisnes.local_data.title }}</h3>
                        </router-link>
                    </div>
                    <div class="caption">
                        <span v-html="bisnes.local_data.short_description"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import generalInfo from './GeneralInfoComponent'

    export default {
        props: [
            'article_prop'
        ],
        data: function () {
            return {
                local_businesses: [],
            }
        },
        components: {
            generalInfo
        },
        mounted() {
            this.get_local_bisnes_for_article()
        },
        methods: {
            get_local_bisnes_for_article(){
                axios
                .get('/bisnes/get_local_bisnes_for_article/' + this.$route.params.url_title + '/' + localStorage.getItem('lang'))
                .then(response => {
                    this.local_businesses = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>

<style>

</style>