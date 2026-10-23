<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link :to="{name: 'studia'}"><i class="fa fa-home" aria-hidden="true"></i> {{ $t('guide.breadcrumb home') }}</router-link></li>
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
            this.page = this.general_pathname.split("/").pop();
            var path = this.general_pathname.split("/")[1]
            
            this.path = path

            if(this.path == 'film'){
                this.path_url = 'studia'
            }
            else{
                this.path_url = path+'s'
            }
        },
    }
</script>

