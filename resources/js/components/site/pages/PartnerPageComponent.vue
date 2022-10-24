<template>
    <div class="container">
        <articlPage :article="partner" />

        <metaData 
            :title = "partner[0].title"
            :description = "partner[0].description"
            :image = "'../../../../public/images/partner_img/'+partner.image"
        />
    </div>
</template>

<script>
    import articlPage from '../items/ArticleComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                partner: [],
            }
        },
        components: {
            articlPage,
            metaData
        },
        mounted() {
            this.get_partner()
        },
        watch: {
            '$route' (to, from) {
                this.get_partner(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_partner(){
                axios
                .get('../api/article/tech_tip/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.partner = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>