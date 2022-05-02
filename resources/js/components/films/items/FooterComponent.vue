<template>
    <div class="footer"> 
        <div class="container"> 
            <div class="row">

                <div class="col-md-4 display-none-720px">
                    <div class="f-about" style='color: #fff;'>
                    
                    <h4>{{__ ('about us')}}</h4>
                    
                    <!-- {!!$site -> short_description!!}
                    <a href="{{route('about_us_page')}}">More</a> -->
                        
                    </div>
                </div>

                <div class="col-md-4 display-none-720px">
                    <div class="f-links">
                        <h4>{{__ ('site.links')}}</h4>
                        <ul>
                            <li> <a href="">{{__ ('site.shop menu products')}}</a> </li>
                            <li> <a href="">{{__ ('site.shop menu about us')}}</a> </li>
                            <li> <a href="">{{__ ('site.shop login')}}</a> </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row f-links">
                        <div class="flick">
                            <h4>{{__ ('site.share')}}</h4>
                            <!-- <share_component></share_component> -->
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
    export default {
        data: function () {
            return {
                num: 0
            };
        },
        components: {
            followComponent,
        },
        mounted() {
            // this.num = Math.floor(Math.random() * 11);
        },
        methods: {
            // follow()
        }
    }
</script>