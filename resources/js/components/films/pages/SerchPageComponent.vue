<template>
    <div>
        <div class="container">

            <h1  class='serch_title'>Search page</h1>

            <div class="row">
                <div class="col-md-12">
                    <p> The Search results for your query is:<b> {{ this.$route.query.query }} </b></p>

                    
                </div>
            </div>
        </div>

        <metaData 
            :title = "'Climbing films'"
            :description = "'Rock climbing, mountaineering and other outdoor actyvity films'"
            :image = "'../../../../public/images/meta_img/films.jpg'"
        />
    </div>
</template>

<script>
    // import articleSerchingCard from '../items/cards/ArticleSerchingCardComponent'
    import metaData from '../items/MetaDataComponent'
    export default {
        components: {
            metaData,
        },
        data: function () {
            return {
                articles: [],
            };
        },
        mounted() {
            this.get_serching_data();
        },
        watch: {
            '$route' (to, from) {
                this.get_serching_data();
                window.scrollTo(0,0);
            }
        },
        methods: {
            get_serching_data() {
                axios
                .get('../api/films_search/' + this.$route.query.query)
                .then(response => {
                    this.articles = response.data
                })
                .catch(error =>{
                })
            }
        }
    }
</script>
