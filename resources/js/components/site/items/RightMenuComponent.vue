<template>
    <!-- <div> -->
        <div :class='"col-sm-3 col-sm-offset-1 blog-sidebar display-none-720px "+[right_navbar_class]'>
            <nav class="navbar fading-side-menu">
                <ul class="list-unstyled">
                    <li>
                        <h4>{{ $t('article navigation menu') }}</h4>
                    </li>

                    <li>
                        <a href="#description">
                            <span class="text-primary">{{ $t('nav description') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="#sectors">
                            <span class="text-primary">{{ $t('nav sectors') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="#gallery">
                            <span class="text-primary">{{ $t('nav gallery') }}</span>
                        </a>
                    </li>
                
                    <li>
                        <a href="#comments">
                            <span class="text-primary">{{ $t('nav comments') }}</span>
                        </a>
                    </li>
                
                    <li>
                        <a href="#other">
                            <span class="text-primary">{{ $t('nav similar articles') }}</span>
                        </a>
                    </li>
                
                </ul>
            </nav>

            <rightAd />

        </div>
    <!-- </div> -->
</template>

<script>
    export default {
        name: "Article Right Navigation Menu",
        data(){
            return{
                right_navbar_class: '',
            }
        },
        mounted() {
            this.margin_bottom_position = document.body.offsetHeight - window.scrollY

            if(this.margin_bottom_position <= 1900){
                this.right_navbar_class = ''
            }
            else{
                this.right_navbar_class = 'right_navigarion_menu_fixed_on_scrine'
            }
        },
        // watch: {
        //     '$route' (to, from) {
        //         // this.$refs.SArticles.test();
        //         // this.$refs.SimilarArticles.get_same_articles();
        //     }
        // },
        methods: {
            // test(){
            //     console.log(Math.random())
            // },
            handleScroll (event) {
                this.margin_bottom_position = document.body.offsetHeight - window.scrollY

                if(this.margin_bottom_position <= 1900){
                    this.right_navbar_class = ''
                }
                else{
                    this.right_navbar_class = 'right_navigarion_menu_fixed_on_scrine'
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
</style>
