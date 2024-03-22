<template>
    <span>
        <div class="h-recent-work" v-if="speciales != [] && speciales">
            <div class="container">
                <h2 class='index_h2' id="news">{{ $t('guide.title.special_article')}}</h2>
                <div class="bar"><i class="fa fa-exclamation" aria-hidden="true"></i></div>
                <!-- <h3 class="article_list_short_description"> Warning </h3> -->

                <span v-if="speciales != []">
                    <specialArticleCard 
                        :speciales_prop="speciales"
                    />
                </span>

            </div>
        </div>
    </span>  
</template>

<script>
    import specialArticleCard from './cards/SpecialArticleCard'

    export default {
        components: {
            specialArticleCard
        },
        props: [
            // 'article',
        ],
        data: function () {
            return {
                speciales: []
            };
        },
        mounted() {
            this.get_special()
        },
        watch: {
            '$route' (to, from) {
                this.get_special()

                window.scrollTo(0,0)
            }
        },
        methods: {
            get_special(){
                axios
                .get('/articles/special/'+localStorage.getItem('lang'))
                .then(response => {
                    this.speciales = response.data[0]
                })
                .catch(error =>{
                })
            },
        }
    }
</script>