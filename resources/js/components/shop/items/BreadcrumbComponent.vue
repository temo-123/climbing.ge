<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link :to="{name: 'catalog'}"><i class="fa fa-home" aria-hidden="true"></i> {{ $t('guide.breadcrumb home') }}</router-link></li>
            <li class="breadcrumb-item"><router-link :to="{name: path_url}">{{ pretty(path) }}</router-link></li>
            <li class="breadcrumb-item active" aria-current="page">{{ pretty(page) }}</li>
        </ol>
    </nav>
</template>

<script>
    export default {
        data(){
            return{
                general_pathname: window.location.pathname,

                path: '',
                page: '',

                path_url: '',
            }
        },
        methods: {
            // URL slugs like "Climbing_In_Sveri(Chiatura)" → "Climbing In Sveri (Chiatura)"
            pretty(slug) {
                let text = String(slug || '');
                try { text = decodeURIComponent(text); } catch (e) {}
                text = text.replace(/[_-]+/g, ' ').replace(/\s*\(/g, ' (').trim();
                return text.charAt(0).toUpperCase() + text.slice(1);
            },
        },
        mounted() {
            var segments = this.general_pathname.split("/").filter(s => s.length > 0)
            var locales = ['en', 'ka']
            var offset = locales.includes(segments[0]) ? 1 : 0

            this.page = segments[segments.length - 1] || ''
            var path = segments[offset] || ''
            this.path = path

            if (path == 'product') {
                this.path_url = 'catalog'
            } else {
                this.path_url = path + 's'
            }
        },
    }
</script>

