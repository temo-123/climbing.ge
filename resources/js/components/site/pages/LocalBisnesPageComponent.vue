<template>
    <div class="container">
        <bisnesPage :bisnes="bisnes" />
    
        <metaData 
            :title = "bisnes.locale_data.title"
            :description = "bisnes.locale_data.description"
            :image = "'/public/images/event_img/'+bisnes.images[0].image"
        />
    </div>
</template>

<script>
    import bisnesPage from '../items/BisnesComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                bisnes: [],
            }
        },
        components: {
            bisnesPage,
            metaData,
        },
        mounted() {
            this.get_bisnes()
        },
        methods: {
            get_bisnes(){
                axios
                .get('../../api/bisnes/get_local_bisnes_in_page/' + this.$route.params.url_title + '/' + localStorage.getItem('lang'))
                .then(response => {
                    this.bisnes = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>