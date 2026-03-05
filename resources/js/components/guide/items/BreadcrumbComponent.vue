<template>
    <ol class="breadcrumb" style="padding: 0; font-size: 75%;">
        <li><router-link :to="{name: 'index'}">{{ $t("guide.breadcrumb home") }}</router-link></li>
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
            this.page = this.general_pathname.split("/").pop();
            var path = this.general_pathname.split("/")[1]
            
            this.path = path

            if(this.path == 'news' || this.path == 'event' || this.path == 'tech_tip' || this.path == 'partner'){
                this.path_url = 'index'
            }
            else{
                this.path_url = path+'s'
            }
        },
    }
</script>

<style>

</style>