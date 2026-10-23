<template>
    <li class="dropdown cg-switcher">
        <a class="cg-icon-btn" role="button" data-bs-toggle="dropdown" aria-expanded="false" :title="$t('global.switcher.open')">
            <i class="fa fa-th" aria-hidden="true"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end cg-switcher__menu">
            <div class="cg-dropdown-label">{{ $t('global.switcher.title') }}</div>
            <div class="cg-switcher__grid">
                <a
                    v-for="item in services"
                    :key="item.key"
                    class="cg-switcher__item"
                    :class="['cg-service-' + item.theme, { 'is-current': item.theme === current }]"
                    @click="open(item)"
                >
                    <span class="cg-switcher__icon"><i :class="'fa ' + item.icon" aria-hidden="true"></i></span>
                    <span class="cg-switcher__text">
                        <strong>{{ $t('global.switcher.' + item.theme) }}</strong>
                        <small>{{ item.theme === current ? $t('global.switcher.current') : $t('global.switcher.' + item.theme + '_desc') }}</small>
                    </span>
                </a>
            </div>
        </div>
    </li>
</template>

<script>
    import go_to_service_mixin from '../../mixins/go_to_service_mixin.js'

    // One menu for jumping between climbing.ge subdomains — replaces the row of
    // unlabeled service icons every navbar used to carry.
    export default {
        name: 'ServicesSwitcher',
        mixins: [go_to_service_mixin],
        props: {
            current: { type: String, default: '' },
        },
        computed: {
            services() {
                return [
                    { key: 'guid', theme: 'guide', icon: 'fa-map-o' },
                    { key: 'shop', theme: 'shop', icon: 'fa-shopping-bag' },
                    { key: 'summit', theme: 'summit', icon: 'fa-flag' },
                    { key: 'blog', theme: 'blog', icon: 'fa-newspaper-o' },
                    { key: 'films', theme: 'films', icon: 'fa-film' },
                ]
            },
        },
        methods: {
            open(item) {
                this.go_to_service(item.key)
            },
        },
    }
</script>
