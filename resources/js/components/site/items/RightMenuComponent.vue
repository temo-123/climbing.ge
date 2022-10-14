<template>
    <div class="col-sm-3 col-sm-offset-1 blog-sidebar display-none-720px right_navigarion_menu">

        <nav class="navbar fading-side-menu">
            <ul class="list-unstyled">
                <li>
                    <h4>{{ $t('article navigation menu') }}</h4>
                </li>

                <!-- <li>
                    <a href="#description">
                        <span class="fa fa-angle-double-right text-primary">{{ $t('nav description') }}</span>
                    </a>
                </li> -->

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

</template>

<script>
    export default {
        name: "Article Right Navigation Menu",
        
    };
</script> 

<style scoped>
.right_navigarion_menu{
    position: fixed;
    right: 0;
}
</style>
