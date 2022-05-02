<template>
  
</template>

<script>
    export default {
        inject:['siteData'],

        props:[
            'title',
        ],
        data(){
            return{
                locale: ''
            }
        },
        metaInfo() {
            return {
                title: this.title,
                // title: 'test title',
                htmlAttrs: {
                    lang: 'en-US'
                },
                link: [
                    { rel: 'stylesheet', href: '/css/index.css' },
                    { rel: 'canonical', href: 'climbing.loc' }
                ],
                meta: [
                    {name: 'viewport', content: 'width=device-width, initial-scale=1'},
                    { charset: 'utf-8' },
                    { name: 'description', content: 'An example Vue application with vue-meta.' },
                    { title: 'Default App Title',titleTemplate: '%s | vue-meta Example App'},
                    { canonical: 'Default App Title',content: '%s | vue-meta Example App'},

                    // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
                    // OpenGraph data (Most widely used)
                    {property: 'og:title', content: 'My Page Title ← My Site'},
                    {property: 'og:site_name', content: 'My Site'},
                    // The list of types is available here: http://ogp.me/#types
                    {property: 'og:type', content: 'website'},
                    // Should the the same as your canonical link, see below.
                    {property: 'og:url', content: 'https://www.my-site.com/my-special-page'},
                    {property: 'og:image', content: '../../../../public/images./meta_images/outdoor.jpg'},
                    // Often the same as your meta description, but not always.
                    {property: 'og:description', content: 'I have things here on my site.'},

                    // Twitter card
                    {name: 'twitter:card', content: 'summary'},
                    {name: 'twitter:site', content: 'https://www.my-site.com/my-special-page'},
                    {name: 'twitter:title', content: 'My Page Title ← My Site'},
                    {name: 'twitter:description', content: 'I have things here on my site.'},
                    // Your twitter handle, if you have one.
                    {name: 'twitter:creator', content: '@alligatorio'},
                    {name: 'twitter:image:src', content: '../../../../public/images./meta_images/outdoor.jpg'},

                    // Google / Schema.org markup:
                    {itemprop: 'name', content: 'My Page Title ← My Site'},
                    {itemprop: 'description', content: 'I have things here on my site.'},
                    {itemprop: 'image', content: '../../../../public/images./meta_images/outdoor.jpg'}
                ]
            }
        },
    }
</script>

<style>

</style>