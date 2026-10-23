<template>
    <div class="container">
        <div class="cg-offroute">
            <div class="cg-offroute__scene">
                <!-- 404: the lead-fall simulation; 403 / 500: animated scene images -->
                <off-route-scene v-if="code === 404" />
                <cg-scene v-else :name="String(code)" />
            </div>

            <div class="cg-offroute__text">
                <div class="error-code">{{ code }}</div>
                <span class="cg-eyebrow">{{ code === 404 ? $t('error.404.off_route') : $t('error.' + code + '.eyebrow') }}</span>
                <h1 class="error-title">{{ $t('error.' + code + '.title') }}</h1>
                <p class="error-details">{{ $t('error.' + code + '.description') }}</p>
                <div class="error-actions">
                    <router-link :to="'/'" exact class="btn btn-primary btn-lg">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        {{ $t("error.404.go_home") }}
                    </router-link>
                    <a href="javascript:history.back()" class="btn btn-light btn-lg">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ $t('common.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineAsyncComponent } from 'vue'
    // the animated scene (+ its simulation) is a separate chunk, loaded only on a 404
    const OffRouteScene = defineAsyncComponent(() => import('./OffRouteSceneComponent.vue'))

    // every service routes /403 and /500 to this component too
    const CODES = { forbidden: 403, 'server-error': 500 }

    export default {
        components: {
            OffRouteScene,
        },
        computed: {
            code() {
                return CODES[this.$route && this.$route.name] || 404
            },
        },
        mounted() {
            document.querySelector('body').style.marginLeft = '0';
            const nav = document.querySelector('.admin_page_header_navbar');
            if (nav) nav.style.marginLeft = '0';
        },
    }
</script>

<style>

</style>