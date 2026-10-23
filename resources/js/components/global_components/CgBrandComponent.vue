<template>
    <router-link :to="to" class="cg-brand" exact>
        <span class="cg-brand__mark">
            <!-- two peaks + summit flag: the climbing.ge mark -->
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M2 20 9 8l3.2 5.4L15 9l7 11H2Z" fill="#fff" />
                <path d="M9 8l-2.1 3.6 1.1-.5 1 .9 1-.9 1.1.5Z" fill="rgba(21,33,43,.35)" />
                <path d="M15 9V3.6l3.4 1.3L15 6.2" fill="#ffc233" />
            </svg>
        </span>
        <span class="cg-brand__text">
            climbing.ge
            <small>{{ $t('global.brand.' + service) }}</small>
        </span>
    </router-link>
</template>

<script>
    export default {
        name: 'CgBrand',
        props: {
            service: { type: String, default: 'guide' },
            to: { type: [String, Object], default: '/' },
        },
    }
</script>
