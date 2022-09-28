<template>
    <div class="footer"> 
        <div class="container"> 
            <footerAd />
            <div class="row">

                <div class="col-md-4 display-none-720px">
                    <div class="f-about" style='color: #fff;'>
                    
                    <h4>{{ $t('about us')}}</h4>
                    
                    <!-- {!!$site -> short_description!!}
                    <a href="{{route('about_us_page')}}">More</a> -->
                        
                    </div>
                </div>

                <div class="col-md-4 display-none-720px">
                    <div class="f-links">
                        <h4>{{ $t('site.links')}}</h4>
                        <ul>
                            <li> <a href="">{{ $t('site.shop menu products')}}</a> </li>
                            <li> <a href="">{{ $t('site.shop menu about us')}}</a> </li>
                            <li> <a href="">{{ $t('site.shop login')}}</a> </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row f-links">
                        <div class="flick">
                            <h4>{{ $t('site.share')}}</h4>
                            
                            <share />
                        </div>
                    </div>

                    <followComponent />
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import followComponent from '../../global_components/FollowComponent.vue'
    import share from '../../global_components/ShareComponent.vue'
    export default {
        data: function () {
            return {
                num: 0
            };
        },
        components: {
            followComponent,
            share
        },
        mounted() {
            // this.num = Math.floor(Math.random() * 11);
        },
        methods: {
            // follow()
        }
    }
</script>