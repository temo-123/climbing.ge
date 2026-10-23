<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link :to="{name: 'index'}"><i class="fa fa-home" aria-hidden="true"></i> {{ $t("guide.breadcrumb home") }}</router-link></li>
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
            // segments without the optional locale prefix (/ka/outdoor/x → outdoor, x)
            var segments = this.general_pathname.split("/").filter(Boolean)
            if (segments.length > 1 && this.$route.params.locale && segments[0] === this.$route.params.locale) {
                segments.shift()
            }
            this.page = segments[segments.length - 1] || ''
            var path = segments[0] || ''

            this.path = path

            // list page for this category ("outdoor" → "outdoors"); categories without
            // a list route (news, events, tech tips, partners, special articles…)
            // link home instead — a missing route name made the whole breadcrumb fail
            var list_route = path + 's'
            if(this.path == 'news' || this.path == 'event' || this.path == 'tech_tip' || this.path == 'partner' || !this.$router.hasRoute(list_route)){
                this.path_url = 'index'
            }
            else{
                this.path_url = list_route
            }
        },
    }
</script>

