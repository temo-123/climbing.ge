<template>
    <div :class='"col-xs-3 col-xs-offset-1 blog-sidebar display-none-720px "+[right_navbar_class]'>
        <div class="row">
            <div class="col-sm-10 col-md-10">
                <div class="thumbnail" v-for="article in bisnes_articles" :key="article.id">
                    <router-link style="font-size: 1.5em;" :to="'../../'+article.category+'/'+article.url_title" exact >
                        <img :src="'../../../images/'+article.category+'_img/' + article.image" :alt="article[0][0].title">
                    </router-link>
                    <div class="caption">
                        <router-link style="font-size: 1.5em;" :to="'../../'+article.category+'/'+article.url_title" exact >
                            <h3>{{ article[0][0].title }}</h3>
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

            this.get_bisnes_article()
        },
        watch: {
            '$route' (to, from) {
                this.get_bisnes_article()
            }
        },
        methods: {
            get_bisnes_article(){
                // alert('ddd')
                axios
                .get('../../../api/article/get_article/for_bisnes_page/' + localStorage.getItem('lang') + '/' + this.$route.params.url_title)
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
.right_navigarion_menu_fixed_on_scrine{
    position: fixed;
    right: 0;
}
.right_navigarion_menu_fixed_on_top{
    /* position: fixed; */
    right: 0;
}
.right_navigarion_menu_fixed_on_bottom{
    /* position: fixed; */
    right: 0;
}
.caption h3{
margin: 0;
}
</style>
