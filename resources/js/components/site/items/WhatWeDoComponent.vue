<template>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <ul class="right-text">
                <li>
                    <h4>
                        <a href=" route('outdoor_list') }}">
                            <strong>{{__ ('title outdoor climbing')}}</strong>
                            <img alt='outdoor climbing image' class="index_category_right" src="images/site_img/logo/outdoor.png">
                        </a>
                    </h4>

                    <div class="content-text">
                            $site -> outdoor_description!!}
                    </div>
                </li>
                <div class="clearfix"></div>
                <li>
                    <h4>
                        <a href=" route('indoor_list') }}">
                            <strong>{{__ ('title indoor climbing')}}</strong>
                            <img alt='indoor climbing image' class="index_category_right" src="images/site_img/logo/indoor.png">
                        </a>
                    </h4>

                    <div class="content-text">
                            $site -> indoor_description!!}
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <ul class="left-text">
                <li>
                    <h4>
                        <a href=" route('ice_list') }}">
                            <strong>{{__ ('title ice climbing')}}</strong>
                            <img alt='indoor climbing image' class="index_category_left" src="images/site_img/logo/indoor.png">
                        </a>
                    </h4>

                    <div class="content-text">
                            $site -> ice_description!!}
                    </div>
                </li>
                <div class="clearfix"></div>
                <li>
                    <h4>
                        <a href=" route('mount_list') }}">
                            <strong>{{__ ('title mountaineering')}}</strong>
                            <img alt='mountaineering image' class="index_category_left" src="images/site_img/logo/mount.png">
                        </a>
                    </h4>

                    <div class="content-text">
                            $site -> mount_description!!}
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>