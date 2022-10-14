<template>
    <div class="container">
        <articlPage :article="other" />

        <metaData 
            :title = "other[0].title"
            :description = "other[0].description"
            :image = "'../../../../public/images/other_img/'+other.image"
        />
    </div>
</template>

<script>
    import articlPage from '../items/ArticleComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                other: [],
            }
        },
        components: {
            articlPage,
            metaData
        },
        mounted() {
            this.get_other()
        },
        methods: {
            get_other(){
                axios
                .get('../api/article/other/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.other = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>