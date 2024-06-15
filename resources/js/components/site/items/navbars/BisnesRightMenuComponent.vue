<template>
    <div :class='"col-sm-12 col-md-3 col-xs-offset-1 blog-sidebar"+[right_navbar_class]'>
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <div class="thumbnail" v-for="bisnes_article in bisnes_articles" :key="bisnes_article.global_data.id">
                    <router-link style="font-size: 1.5em;" :to="'../../'+bisnes_article.global_data.category+'/'+bisnes_article.global_data.url_title" exact >
                        <img v-if="bisnes_article.global_data.image != null" :src="'../../../images/'+bisnes_article.global_data.category+'_img/' + bisnes_article.global_data.image" :alt="bisnes_article.global_data.title">
                        <img v-else :src="'/public/images/site_img/image.png'" :alt="bisnes_article.global_data.title">
                    </router-link>
                    <div class="caption">
                        <router-link style="font-size: 1.5em;" :to="'../../'+bisnes_article.global_data.category+'/'+bisnes_article.global_data.url_title" exact >
                            <h3>{{ bisnes_article.locale_data.title }}</h3>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Bisnes Page Right Navigation Menu",
        data(){
            return{
                right_navbar_class: '',
                bisnes_articles: [],
            }
        },
        mounted() {
            this.get_bisnes_article()

            this.margin_bottom_position = document.body.offsetHeight - window.scrollY

            if(document.body.offsetHeight > 2000){
                if(this.margin_bottom_position <= 1900){
                    this.right_navbar_class = ''
                }
                else{
                    this.right_navbar_class = 'right_navigarion_menu_fixed_on_scrine'
                }
            }
            else{
                this.right_navbar_class = ''
            }

        },
        watch: {
            // '$route' (to, from) {
            //     this.get_bisnes_article()
            // }
        },
        methods: {
            get_bisnes_article(){
                axios
                .get('/article/get_article_for_bisnes_page/' + localStorage.getItem('lang') + '/' + this.$route.params.url_title)
                .then(response => {
                    this.bisnes_articles = response.data
                })
                .catch(error =>{
                })
            },
            handleScroll (event) {
                this.margin_bottom_position = document.body.offsetHeight - window.scrollY
                
                if(document.body.offsetHeight > 2000){
                    if(this.margin_bottom_position <= 1900){
                        this.right_navbar_class = ''
                    }
                    else{
                        this.right_navbar_class = 'right_navigarion_menu_fixed_on_scrine'
                    }
                }
                else{
                    this.right_navbar_class = ''
                }
            }
        },
        created () {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed () {
            window.removeEventListener('scroll', this.handleScroll);
        },
    };
</script> 

<style scoped>
    /* .right_navigarion_menu_fixed_on_scrine{
        position: fixed;
        right: 0;
    }
    .right_navigarion_menu_fixed_on_top{
        right: 0;
    }
    .right_navigarion_menu_fixed_on_bottom{
        right: 0;
    } */
    .caption h3{
        margin: 0;
    }
</style>
