<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb user_breadcrumb">
            <li class="breadcrumb-item">
                <router-link :to="{name: 'home'}">Home</router-link>
            </li>
            <li class="breadcrumb-item" v-if="path != page">
                <a href="#">{{ path }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ page }}</li>
        </ol>
    </nav>
</template>

<script>
    export default {
        props: [
            // 
        ],
        components: {
            //
        },
        methods: {
            //
        },
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

            // if(this.path == 'news' || this.path == 'event' || this.path == 'tech_tip' || this.path == 'partner'){
            //     this.path_url = 'index'
            // }
            // else{
            //     this.path_url = path+'s'
            // }
        },
    }
</script>

<style>
.user_breadcrumb {
    background-color: #e9ecef !important;
}
</style>