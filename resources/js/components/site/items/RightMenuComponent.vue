<template>
    <div>
        <div class="col-sm-3 col-sm-offset-1 blog-sidebar display-none-720px right_navigarion_menu">

            <div v-if="Math.random() > 0.5">
  Now you see me
</div>
<div v-else>
  Now you don't
</div>
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
    </div>
</template>

<script>
    export default {
        name: "Article Right Navigation Menu",
        mounted() {
            // console.log(window.screenX)
            // window.scroll(0, 0);
        },
        methods: {
            test(){
                console.log(Math.random())
            },
        }
    };
</script> 

<style scoped>
.right_navigarion_menu{
    position: fixed;
    right: 0;
}
</style>
