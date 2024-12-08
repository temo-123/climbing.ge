<template>
    <div :class="'navbar_box'">
        <div class="navbar_ampty_space" @click="open_menu()"></div>
        <div class="sidebar left_sidebar">
            <header>Filtred</header>

            <ul  style="padding-left: 0px;">
                <li >
                    <a href="#" @click="show_item('category')" class="dropdown-toggle" >Filtr by category</a>
                    <ul :class="'category menu_opening_list'" style="display: none;">
                        <li class="menu_item">
                            <select class="form-control" v-model="filter_category" name="product_modification_for_cart" @click="sortByCategories()">
                                <option>All</option>
                                <option v-for="category in categories" :key='category.id' :value="category.id">{{ category.us_name }}</option> 
                            </select>
                        </li>
                    </ul>
                </li>

                <li >
                    <a href="#" @click="show_item('brand')" class="dropdown-toggle" >Filtr by brand</a>
                    <ul :class="'brand menu_opening_list'" style="display: none;">
                        <li class="menu_item">
                            <select class="form-control" v-model="filter_category" name="product_modification_for_cart" @click="sortByCategories()">
                                <option>All</option>
                                <option v-for="category in categories" :key='category.id' :value="category.id">{{ category.us_name }}</option> 
                            </select>
                        </li>
                    </ul>
                </li>

                <li >
                    <a href="#" @click="show_item('sale_type')" class="dropdown-toggle" >Filtr by sale type</a>
                    <ul :class="'sale_type menu_opening_list'" style="display: none;">
                        <li class="menu_item">
                            <select class="form-control" name="product_modification_for_cart">
                                <option>All</option>
                                <option :value="'Custom production'">Custom production</option> 
                                <option :value="'Online sale'">Online sale</option> 
                            </select>
                        </li>
                    </ul>
                </li>

                <li >
                    <a href="#" @click="show_item('min_price')" class="dropdown-toggle" >Filtr by minimal price</a>
                    <ul :class="'min_price menu_opening_list'" style="display: none;">
                        <li class="menu_item">
                            <input class='min_price_range price_range width_100' type="range" min="0" max="999" v-model="min_price" step="10">

                            <div class="row price_range_text text-center">
                                <div class="col-sm-10">Min price - </div>
                                <div class="col-sm-2">
                                    <input
                                        type="text"
                                        v-model="min_price"
                                        maxlength ="6"
                                        :style="'border: 0;'"
                                    /> 
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <li >
                    <a href="#" @click="show_item('max_price')" class="dropdown-toggle" >Filtr by maximal price</a>
                    <ul :class="'max_price menu_opening_list'" style="display: none;">
                        <li class="menu_item">
                            <input class="max_price_range price_range width_100" type="range" min="0" max="999" v-model='max_price' value="1000" step="10">

                            <div class="row price_range_text text-center ">
                                <div class="col-sm-10">Max price - </div>
                                <div class="col-sm-2">
                                    <input
                                        type="text"
                                        v-model="max_price"
                                        maxlength ="6"
                                        :style="'border: 0;'"
                                    /> 
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>

            <ul style="padding-left: 0px;">
                <li>
                    <a @click="serReangSlider()">Filtr</a>
                </li>
            </ul>


            <ul style="padding-left: 0px;" @click="open_menu()">
                <li>
                    <a>{{ $t('user.menu.close') }}</a>
                </li>
            </ul>
            
        </div>
    </div>
</template>

<script>
    import { navbar } from '../../../../mixins/navbar_pages_mixin.js'
    export default {
        mixins: [
          navbar
        ],
	    name: 'leftMenu',
        data(){
            return {
                menu: false,
                
                min_price: 0,
                max_price: 999,
                filter_category: 'All',
                
                categories: [],
                products_loading: true,
                product_modal: false,
                modalClass: '',
            }
        },
        mounted() {
            this.open_menu()
        },
        unmounted() {
            // this.window_size()
        },
        methods: {
            open_menu: function(){
                if (this.menu) {
                    this.menu = false
                    document.querySelector('.navbar_box').style.display = '';
                }
                else{
                    this.menu = true
                    document.querySelector('.navbar_box').style.display = 'none';
                }
            },

            show_item(item_class){
              if (document.querySelector('.'+item_class).style.display == 'none') {
                document.querySelector('.'+item_class).style.display = '';
              }
              else {
                document.querySelector('.'+item_class).style.display = 'none'
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
        }
    }
</script>

<style scoped>
 .menu_opening_list{
    background-color: rgb(4, 53, 75);
    transition: 0.4s;
    height: 104px;
    padding-top: 35px;
 }
.navbar_ampty_space{
    position: fixed;
    z-index: 1091;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    margin-left: 22em;
}
.navbar_box{
    position: fixed;
    z-index: 1091;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  left: 0;
  width: 22em;
  height: 100%;
  background: #042331;
  transition: all .5s ease;
  z-index: 1090;
  opacity: 1;
}
.left_sidebar {
  overflow:auto;
}
.sidebar header {
  font-size: 22px;
  color: white;
  line-height: 54.5px;
  text-align: center;
  background: #063146;
  user-select: none;
}
.menu_item{
  display: block;
  height: 100%;
  width: 100%;
  line-height: 65px;
  font-size: 20px;
  color: white;
  padding-right: 20px;
  padding-left: 20px;
  box-sizing: border-box;
  border-bottom: 1px solid black;
  transition: .4s;
  
}
.sidebar ul a{
  display: block;
  height: 100%;
  width: 100%;
  line-height: 65px;
  font-size: 20px;
  color: white;
  padding-left: 40px;
  box-sizing: border-box;
  border-bottom: 1px solid black;
  transition: .4s;
  
}
ul li:hover a{
  padding-left: 50px;
}
.sidebar ul a i{
  margin-right: 16px;
}
</style>