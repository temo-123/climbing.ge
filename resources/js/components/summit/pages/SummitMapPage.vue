<template>
    <div>
        <div class="h-recent-work">
            <div class="container">

                <h1 class="index_h2">{{ $t('summit.menu.map') }}</h1>

                <div class="bar"><i class="fa fa-map-marker"></i></div>

                <h3 class="article_list_short_description">
                    {{ $t('summit.summit_map_description') }}
                </h3>

                <div class="text-center py-5 text-muted">
                    <i class="fa fa-map fa-4x mb-3"></i>
                    <p>Map coming soon.</p>
                    <router-link to="/summits/list" class="btn btn-default btn-send main-btn">
                        <i class="fa fa-list"></i> {{ $t('summit.menu.list') }}
                    </router-link>
                </div>

            </div>
        </div>

        <metaData
            :title="$t('summit.menu.map') + ' – Summit Climbing Georgia'"
            :description="$t('summit.summit_map_description')"
            :image="'/public/images/meta_img/mountain.jpg'"
        />
    </div>
</template>

<script>
import metaData from '../items/MetaDataComponent.vue';

export default {
    components: { metaData }
};
</script>
