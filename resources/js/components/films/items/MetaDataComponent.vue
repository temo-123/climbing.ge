<template>
  
</template>

<script>
    export default {
        props:[
            'title',
            'description',
            'image'
        ],
        data(){
            return{
                locale: localStorage.getItem('lang'),

                mix_site_url: process.env.MIX_SITE_URL,
                mix_app_ssh: process.env.MIX_APP_SSH,
                mix_site_name: process.env.MIX_SITE_NAME
            }
        },
        metaInfo() {
            return {
                title: this.title + ' - ' + 'films.' + this.mix_site_url,
                // title: 'test title',
                htmlAttrs: {
                    lang: this.locale
                },
                link: [
                    // { rel: 'stylesheet', href: '/css/index.css' },
                    { rel: 'canonical', href: '/' }
                ],
                meta: [
                    {name: 'viewport', content: 'width=device-width, initial-scale=1'},
                    { charset: 'utf-8' },
                    { name: 'description', content: this.description },
                    { title: 'Default App Title',titleTemplate: '%s | '+this.title + ' - ' + 'films.' + this.mix_site_url},
                    { canonical: 'Default App Title',content: '%s | '+this.title + ' - ' + 'films.' + this.mix_site_url},

                    // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
                    // OpenGraph data (Most widely used)
                    {property: 'og:title', content: this.title + ' - ' + 'films.' + this.mix_site_url},
                    {property: 'og:site_name', content: this.mix_site_name},
                    // The list of types is available here: http://ogp.me/#types
                    {property: 'og:type', content: 'website'},
                    // Should the the same as your canonical link, see below.
                    {property: 'og:url', content: this.mix_app_ssh+'films.' + this.mix_site_url},
                    {property: 'og:image', content: this.image},
                    // Often the same as your meta description, but not always.
                    {property: 'og:description', content: this.description},

                    // Twitter card
                    {name: 'twitter:card', content: 'summary'},
                    {name: 'twitter:site', content:  this.mix_app_ssh+'films.' + this.mix_site_url},
                    {name: 'twitter:title', content: this.title + ' - ' + 'films.' + this.mix_site_url},
                    {name: 'twitter:description', content: this.description},
                    // Your twitter handle, if you have one.
                    {name: 'twitter:creator', content: '@alligatorio'},
                    {name: 'twitter:image:src', content: this.image},

                    // Google / Schema.org markup:
                    {itemprop: 'name', content: this.title + ' - ' + 'films.' + this.mix_site_url},
                    {itemprop: 'description', content: this.description},
                    {itemprop: 'image', content: this.image}
                ]
            }
        },
    }
</script>

<style>

</style>