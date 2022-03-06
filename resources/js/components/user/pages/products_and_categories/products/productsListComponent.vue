<template>
    <div>
        <div class="row list-group">
            <div class="add_buttom">
                <a :href="product_add_url" class="btn btn-primary pull-left" type="submit">New </a>
            </div>

            <div class="form-groupe">
                <button @click="get_products_data" class="btn main-btn pull-right" v-if="!product_is_refresh">Refresh ({{product_reset_id}})</button>
                <span class="badge badge-primare mb-1 pull-right" v-if="product_is_refresh">Updating...</span>
            </div>
        </div>

        <ul class="row list-group">
            <li class="list-group-item" v-for="product in products" :key="product.id">
                <div class="row">
                    <div class="col-xs-2 col-md-2">
                        <img :src="'/public/images/shop_img/' + product.image_1" :alt="product.url_title" class="img-circle img-responsive product_table_img">
                    </div>
                    <div class="col-xs-10 col-md-10">
                        <div class="row">
                            <a href=#> <h3> {{ product.title }} </h3></a>
                        </div>
                        <div class="row">
                            <h3>{{ product.price }} {{ product.currency }}</h3>
                        </div>
                        <div class="row">
                            <h3>Category - {{ product.category }}</h3>
                        </div>

                        <div class="action">
                            <!-- {{-- <div style="float: left;">
                                @if(product.approved == 1)
                                <button type="button" class="btn btn-success btn-xs" title="Approved">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </button>
                                @elseif(product.approved == 1 || product.approved == NULL)
                                <button type="button" class="btn btn-danger btn-xs" title="Approved">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                                @endif
                            </div> --}} -->
                            
                            <div style="float: right;">
                                <a type="button" class="btn btn-primary btn-xs" :href="'product_edit/' + product.id" >
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                                <!-- <form method="post" @submit.prevent="product_del(product.id)">
                                    {{ method_field('DELETE') }}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_token">
                                    <div class="form-group">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">    
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button>
                                    </div>
                                </form> -->
                                <form method="post" @submit.prevent="product_del(product.id)" >
                                    <input type="hidden" name="_token" >
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <!-- <div class="panel panel-default target">
                <div class="panel-heading" contenteditable="false">
                    <h2>You don't have any products</h2>
                </div>
            </div> -->
             
        </ul>
    </div>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'

    export default {
        components: {
            StackModal
        },

        props: [
            "product_get_route",

            "product_name",

            "product_add_url",

            // "product_edit_url",

            // "product_del_url",

            "product_page",
        ],
        
        data() {
            return {
                products: [],

                product_is_refresh: false,

                product_reset_id: 0,

                url_1: "",
            }
        },

        mounted() {
            this.get_products_data();
        },
         
        methods: {
            get_products_data: function(){
                this.product_is_refresh = true

                axios
                .get("/products/get_product_data/")
                .then(response => {
                    this.products = response.data
                    this.product_is_refresh = false
                    this.product_reset_id++

                    // this.get_data_in_table_1();
                })
                .catch(
                    error => console.log(error)
                );

            },

            product_del(itemId) {
                axios
                .post('/products/del/' + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_1()
                })
                .catch(error => console.log(error))
            },
        }
    }
</script>