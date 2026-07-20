<template>
    <li class="service-link-li" v-for="service in services" :key="service.key">
        <a class="service-link-btn" @click="go_to_service(service.key)" :title="service.title">
            <i :class="'fa ' + service.icon" aria-hidden="true"></i>
        </a>
    </li>
</template>

<script>
import go_to_service_mixin from '../../../../mixins/go_to_service_mixin.js'

export default {
    name: 'ServiceLinksComponent',
    mixins: [go_to_service_mixin],
    computed: {
        services() {
            return [
                { key: 'guid', icon: 'fa-map-o', title: this.$t('user.nav.go_to_guidebook') },
                { key: 'shop', icon: 'fa-shopping-bag', title: this.$t('user.nav.go_to_shop') },
                { key: 'summit', icon: 'fa-street-view', title: this.$t('user.nav.go_to_summit') },
                { key: 'blog', icon: 'fa-newspaper-o', title: this.$t('user.nav.go_to_blog') },
            ]
        },
    },
}
</script>

<style scoped>
/* Bootstrap-version-independent by design: the guide/shop/summit subdomains
   run Bootstrap 3, the user dashboard runs Bootstrap 4, and their default
   .navbar-nav li/a spacing rules aren't compatible. Self-contained sizing
   here (mirroring NavBadgesComponent.vue) avoids relying on either. */
.service-link-li {
    display: inline-block;
    vertical-align: middle;
    list-style: none;
}
.service-link-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 15px 10px;
    cursor: pointer;
    color: #fff !important;
    font-size: 1.1em;
    text-decoration: none !important;
    line-height: 20px;
    background: transparent !important;
}
.service-link-btn:hover {
    opacity: 0.75;
}
@media (max-width: 992px) {
    .service-link-li {
        display: block;
    }
}
</style>
