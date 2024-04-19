<template>
    <Head>
        <title>{{ this.title + ' - ' + this.mix_site_url }}</title>

        <meta name="description" :content="this.description">
        <meta name="keywords" content="climbing, georgia, climbing in georgia, rock climbing, rock climing in georgia, mountaineering, mountaineering in georgia"/>

        <meta name="subject" content="Rock climbing and mountaineering in Georgia">
        <meta name="copyright"content="Temo Samsonadze">
        <meta name="language" :content="this.locale">
        <meta name="Classification" content="Sport">
        <meta name="author" :content="'info@' + this.mix_site_url">
        <meta name="reply-to" :content="'info@' + this.mix_site_url">
        <meta name="url" :content="this.mix_app_ssh + this.mix_site_url">

        <meta name="og:title" :content="this.title"/>
        <meta name="og:type" content="Climbing"/>
        <meta name="og:url" :content="this.mix_app_ssh + this.mix_site_url"/>
        <meta name="og:image" :content="this.action_image"/>
        <meta name="og:site_name" :content="this.mix_site_name"/>
        <meta name="og:description" :content="this.description"/>

        <meta name="twitter:title" :content="this.title"/>
        <meta name="twitter:type" content="Climbing"/>
        <meta name="twitter:url" :content="this.mix_app_ssh + this.mix_site_url"/>
        <meta name="twitter:image" :content="this.action_image"/>
        <meta name="twitter:site_name" :content="this.mix_site_name"/>
        <meta name="twitter:description" :content="this.description"/>
    </Head>
</template>
<script>
    import { useHead } from '@unhead/vue'
    import { Head } from '@unhead/vue/components'

    export default {
        props:[
            'title',
            'description',
            'image'
        ],
        components: {
            useHead,
            Head
        },
        data(){
            return{
                locale: localStorage.getItem('lang'),

                mix_site_url: process.env.MIX_SITE_URL,
                mix_app_ssh: process.env.MIX_APP_SSH,
                mix_site_name: process.env.MIX_SITE_NAME,

                action_image: process.env.MIX_APP_SSH + '' + process.env.MIX_SITE_URL + '/' + this.image
            }
        },
        // useHead: {
        //     title: this.title + ' - ' + this.mix_site_url,
        //     meta: [
        //         {
        //             name: 'description',
        //             content: 'My page description',
        //             image: 'action_image'
        //         },
        //     ],
        // },

        metaInfo() {
            return {
                title: this.title + ' - ' + this.mix_site_url,
                htmlAttrs: {
                    lang: this.locale,
                    amp: true
                },
                link: [
                    // { rel: 'stylesheet', href: '/css/index.css' },
                    { rel: 'canonical', href: window.location.pathname }
                ],
                meta: [
                    {name: 'viewport', content: 'width=device-width, initial-scale=1'},
                    { charset: 'utf-8' },
                    { name: 'description', content: this.description },
                    { title: 'Default App Title',titleTemplate: '%s | '+this.title + ' - ' + this.mix_site_url},
                    { canonical: 'Default App Title',content: '%s | '+this.title + ' - ' + this.mix_site_url},

                    // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
                    // OpenGraph data (Most widely used)
                    {property: 'og:title', content: this.title + ' - ' + this.mix_site_url},
                    {property: 'og:site_name', content: this.mix_site_name},
                    // The list of types is available here: http://ogp.me/#types
                    {property: 'og:type', content: 'website'},
                    // Should the the same as your canonical link, see below.
                    {property: 'og:url', content: this.mix_app_ssh+this.mix_site_url},
                    {property: 'og:image', content: this.action_image},
                    // Often the same as your meta description, but not always.
                    {property: 'og:description', content: this.description},

                    // Twitter card
                    {name: 'twitter:card', content: 'summary'},
                    {name: 'twitter:site', content:  this.mix_app_ssh+this.mix_site_url},
                    {name: 'twitter:title', content: this.title + ' - ' + this.mix_site_url},
                    {name: 'twitter:description', content: this.description},
                    // Your twitter handle, if you have one.
                    {name: 'twitter:creator', content: '@alligatorio'},
                    {name: 'twitter:image:src', content: this.action_image},

                    // Google / Schema.org markup:
                    {itemprop: 'name', content: this.title + ' - ' + this.mix_site_url},
                    {itemprop: 'description', content: this.description},
                    {itemprop: 'image', content: this.action_image}
                ]
            }
        },
    }
</script>

<style>

</style>