<template>
    <div :class='"col-sm-3 col-sm-offset-1 display-biger-then-768px right_fixed_menu "+[right_navbar_class]'>

        <nav class="fading-side-menu display-biger-then-768px ">

            <div class="row fading-side-menu local_bisnes display-biger-then-768px" >
                <div class="row">
                    <div class="col-sm-8 fading-side-menu">
                        <h3 class="fading-side-menu navbar_title display-biger-then-768px" >Filtr by category</h3>
                        <select class="form-control" v-model="filter_category" name="product_modification_for_cart" @click="sortByCategories()">
                            <option>All</option>
                            <option v-for="category in categories" :key='category.id' :value="category.id">{{ category.us_name }}</option> 
                        </select>
                    </div>
                    <div class="col-sm-8 fading-side-menu">
                        <h3 class="fading-side-menu navbar_title display-biger-then-768px" >Filtr by sale type</h3>
                        <select class="form-control" name="product_modification_for_cart">
                            <option>All</option>
                            <option :value="'Custom production'">Custom production</option> 
                            <option :value="'Online sale'">Online sale</option> 
                        </select>
                    </div>
                    <div class="col-sm-8 fading-side-menu">
                        <h3 class="fading-side-menu navbar_title display-biger-then-768px" >Filtr by brend</h3>
                        <select class="form-control" name="product_modification_for_cart">
                            <option>All</option>
                            <option :value="'Custom production'">Brend 1</option> 
                            <option :value="'Online sale'">Brend 2</option> 
                        </select>
                    </div>

                    <div class="col-sm-8 fading-side-menu">
                        <input class='min_price_range price_range width_100' type="range" min="0" max="999" v-model="min_price" step="10">

                        <div class="row price_range_text text-center">
                            <div class="col-sm-8">Min price - </div>
                            <div class="col-sm-2">
                                <input
                                    type="text"
                                    v-model="min_price"
                                    maxlength ="6"
                                    :style="'border: 0;'"
                                /> 
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-8 fading-side-menu">
                        <input class="max_price_range price_range width_100" type="range" min="0" max="999" v-model='max_price' value="1000" step="10">

                        <div class="row price_range_text text-center ">
                            <div class="col-sm-8">Max price - </div>
                            <div class="col-sm-2">
                                <input
                                    type="text"
                                    v-model="max_price"
                                    maxlength ="6"
                                    :style="'border: 0;'"
                                /> 
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-8 fading-side-menu">
                        <button class="btn btn-primary" @click="serReangSlider()">Filtr</button>
                    </div>
                </div>
            </div>

        </nav>

    </div>
</template>

<script>
    export default {
        // name: "Article Right Navigation Menu",
        data(){
            return{
                right_navbar_class: '',
                local_businesses: [],
                margin_bottom_position: 0,
                document_body_offsetHeight: document.body.offsetHeight,
                window_scrollY: window.scrollY,

                min_price: 0,
                max_price: 999,
                filter_category: 'All',
                
                categories: [],
            }
        },
        mounted() {
            // this.get_local_bisnes_for_article()
            this.handleScroll()
        },
        watch: {
            '$route' (to, from) {
                // this.get_local_bisnes_for_article()
            }
        },
        methods: {
            scrollToSection(sectionId) {
                const targetElement = document.getElementById(sectionId);
                if (targetElement) {
                    const yOffset = -80; // adjust this value to control the offset from the top
                    const y = targetElement.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            },

            handleScroll (event) {
                this.margin_bottom_position = document.body.offsetHeight - window.scrollY
                
                const menu = document.querySelector('.right_fixed_menu');
                const footer = document.querySelector('.footer');
                const footer__graphic = document.querySelector('.footer__graphic');

                const menuBottom = menu.getBoundingClientRect().bottom;
                const footerTop = footer.getBoundingClientRect().top;
                const footer__graphic_top = footer__graphic.offsetHeight;

                if(this.margin_bottom_position > window.scrollY){
                    this.right_navbar_class = ''
                }
                else if(footerTop - footer__graphic_top - 50 < menuBottom){                    
                    this.right_navbar_class = 'right_navigarion_menu_fixed_on_scrine_bottom'
                }
                else{
                    this.right_navbar_class = 'right_navigarion_menu_fixed_on_scrine'
                }
            },
            
            sortByCategories(){
                let vm = this;
                if (vm.filter_category == 'All') {
                    this.filtred_products = this.products.filter(function (item){
                        return item.max_price >= vm.min_price && item.max_price <= vm.max_price
                    })
                }
                else{
                    let f_products = this.products.filter(function (item){
                        return item.global_product.category_id == vm.filter_category
                    })

                    this.filtred_products = f_products.filter(function (item){
                        return item.max_price >= vm.min_price && item.max_price <= vm.max_price
                    })
                }
            },

            close_product_modal(){
                this.product_modal = false
                this.quick_product = []
            },

            serReangSlider(){
                if(this.min_price == 0 && this.max_price == 0){
                    this.min_price = 0
                    this.max_price = 1000
                }
                else if(this.min_price > this.max_price){
                    let temp = this.max_price
                    this.max_price = this.min_price
                    this.min_price = temp
                }

                this.sortByCategories()
            },

            get_categories(){
                axios
                .get('../api/product_category')
                .then(response => {
                    this.categories = response.data
                })
                .catch(error =>{
                })
            },

            get_product_price_interval(){
                axios
                .get('../api/products/get_products_price_interval')
                .then(response => {
                    // this.max_price = response.data
                })
                .catch(error =>{
                })
            }
        },
        created () {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed () {
            window.removeEventListener('scroll', this.handleScroll);
        },

    };
</script> 

<style scoped>
    .right_navigarion_menu_fixed_on_scrine{
        position: fixed;
        right: 0;
        margin-top: -10%;
    }
    .right_navigarion_menu_fixed_on_scrine_bottom{
        position: absolute;
        right: 0;
        bottom: 570px;
    }
    .caption h3{
        margin: 0;
    }
    .navbar_title{
        font-size: 20px;
        text-align: left;
        margin: 0 0 8% 0;
    }
    .local_bisnes{
        margin-top: 8%;
    }
    .fading-side-menu{
        margin-bottom: 4%;
    }
</style>
