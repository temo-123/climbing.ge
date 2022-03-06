<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h2 class='index_h2'>{{__ ('title ice climbing')}}</h2>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            <h6><span v-html="this.$siteData.other_activity_description"></span></h6>

            <div v-if="this.ices.length > 0">
                <articleComponent 
                    v-for="ice in ices"
                    :key='ice.id'
                    :image_dir="'images/ice_img/'"
                    :article="ice"
                    :route="'ice/'+ice.url_title"
                />
            </div>
            <div v-else>
                <emptyPageComponent />
            </div>
        </div>  
    </div>
</template>

<script>
    import articleComponent from '../items/cards/ArticleCardComponent'
    import emptyPageComponent from '../items/EmptyPageComponent'

    export default {
        data: function () {
            return {
                ices: []
            };
        },
        components: {
            articleComponent,
            emptyPageComponent,
        },
        mounted() {
            this.get_ices()
        },
        methods: {
            get_ices(){
                axios
                .get('../api/ice')
                .then(response => {
                    this.ices = response.data
                })
                .catch(error =>{
                })
            }
        }
    }
</script>

<style>

</style>