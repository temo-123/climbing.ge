<template>
    <div class="container">
        <outdoor :article="outdoor" />

        <metaData 
            :title = "outdoor[0].title"
            :description = "outdoor[0].description"
            :image = "'../../../../public/images/outdoor_img/'+outdoor.image"
        />
    </div>
</template>

<script>
    import outdoor from '../items/OutdoorComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                outdoor: [],
            }
        },
        components: {
            outdoor,
            metaData
        },
        mounted() {
            this.get_outdoor()
        },
        methods: {
            get_outdoor(){
                axios
                .get('../api/article/outdoor/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.outdoor = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>