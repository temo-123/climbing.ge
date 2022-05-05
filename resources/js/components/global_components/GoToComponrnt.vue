<template>
    <li class="display-none-720px">
        <a style="margin-top: -5%; font-size: 120%;" target="_blank" @click="goTo(go_to_page)">
            <i class="fa fa-user-circle"></i>
        </a>
    </li>
</template>

<script>
    export default {
        data: function () {
            return {
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        props:[
            'go_to_page'
        ],
        methods: {
            goTo(page){
                window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page)
            },
        }
    }
</script>

<style>

</style>