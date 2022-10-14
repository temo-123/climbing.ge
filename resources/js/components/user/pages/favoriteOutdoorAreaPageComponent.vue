<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <left-menu />
            </div>
            
            <div class="col-sm-9">
                <div class="row font-italic border-bottom">
                    <div class="col-md-6">
                        <h3 class="mt-3 pb-3 mb-4 ">
                            Faworite climbing spots
                        </h3>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary pull-right" @click="go_to_spots_list('/outdoor')">
                            Go to outdoor spot list
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <img class="card-img-top" src="//placeimg.com/280/180/tech" alt="Card image cap">
                            <div class="card-body">
                            <h5 class="card-title border-bottom pb-3">Card title <a href="#" class="float-right d-inline-flex share"><i class="fas fa-share-alt text-primary"></i></a></h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="float-right">Read more <i class="fas fa-angle-double-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        // props:[
        //     'site_data',
        // ],
        data: function () {
            return {
                spots: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        components: {
            // catalogItem,
        },
        mounted() {
            this.get_spots()
        },
        methods: {
            get_spots(){
                axios
                // .get('../api/get_user_favorite_products')
                // .then(response => {
                //     this.spots = response.data

                //     // this.sortByCategories()
                // })
                // .catch(error =>{
                // })
                // .finally(() => this.products_loading = false);
            },
            go_to_spots_list(page){
                window.open(this.MIX_APP_SSH + '' + this.MIX_SITE_URL + page)
            },
            del_from_faworites(product_id){
                // axios
                // .post('../api/del_from_favorite/'+ product_id)
                // .then(response => {
                //     alert("Product delited from your favorite list!");
                //     this.get_products()
                // })
                // .catch(error =>{
                //     alert("Error");
                // })
            },
            // go_product_list(){
            //     window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL)
            // },
        }
    }
</script>

<style scoped>
</style>