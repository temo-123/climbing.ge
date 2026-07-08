<template>
    <ol class="breadcrumb" style="padding: 0; font-size: 75%;">
        <li><router-link :to="{name: 'catalog'}">{{ $t('guide.breadcrumb home') }}</router-link></li>
        <li><router-link :to="{name: path_url}">{{ path }}</router-link></li>
        <li class="active">{{ page }}</li>
    </ol>
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

<style>

</style>