<template>
    <div class="container" v-if="local_businesses.length != 0">
        <div class="row local_bisnes">
            <h2 class="navbar_title" v-if="local_businesses.length != 0">{{ $t('guide.article_right_nabar.recomended_services') }}</h2>
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
    // import generalInfo from './GeneralInfoComponent'

    export default {
        props: [
            // 'article_prop'
        ],
        data: function () {
            return {
                local_businesses: [],
            }
        },
        components: {
            // generalInfo
        },
        mounted() {
            this.get_local_bisnes_for_article()
        },
        methods: {
            get_local_bisnes_for_article(){
                // Get current language or default to 'en'
                const currentLang = localStorage.getItem('lang') || 'en';
                
                axios
                .get('/get_bisnes/get_local_bisnes_for_article/' + this.$route.params.url_title + '/' + currentLang)
                .then(response => {
                    // Ensure we always get an array
                    if (response.data && Array.isArray(response.data)) {
                        this.local_businesses = response.data;
                        console.log('Local businesses loaded:', this.local_businesses.length);
                    } else {
                        this.local_businesses = [];
                        console.log('No local businesses found or invalid response format');
                    }
                })
                .catch(error => {
                    console.error('Error fetching local businesses:', error);
                    console.log('URL called:', '/api/get_bisnes/get_local_bisnes_for_article/' + this.$route.params.url_title + '/' + currentLang);
                    this.local_businesses = [];
                })
            },
        }
    }
</script>