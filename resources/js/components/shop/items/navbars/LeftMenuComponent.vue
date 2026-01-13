<template>
    <div :class="'navbar_box'">
        <div class="navbar_ampty_space" @click="open_menu()"></div>
        <div class="sidebar left_sidebar">
            <header>
                <i class="fa fa-filter"></i> Filters
                <button @click="open_menu()" class="close-btn"><i class="fa fa-times"></i></button>
            </header>

            <ul  style="padding-left: 0px;">
                <li>
                    <a href="#" @click="show_item('category')" class="dropdown-toggle" >
                        <i class="fa fa-list"></i> Categories <i class="fa fa-chevron-down toggle-icon"></i>
                    </a>
                    <ul :class="'category'" style="background-color: #04354b; display: none; transition: .4s;">
                        <span >
                            <li class="menu_item" style="height: 104px; padding-top: 35px;">
                                <select class="form-control" v-model="filter_category" @change="get_category_subcategories()" name="sort_by_categories" >
                                    <option :value="0">All</option>
                                    <option v-for="category in categories" :key='category.id' :value="category.id">{{ category.us_name }}</option>
                                </select>
                            </li>

                            <li v-for="subcat in subcategories" :key="subcat.id" v-bind:value="subcat.id" class="menu_small_item" @click="sort_by_subcategories(subcat.id)">
                                {{ subcat.us_name }}
                            </li>

                        </span>
                    </ul>
                </li>

                <li >
                    <a href="#" @click="show_item('brand')" class="dropdown-toggle" >
                        <i class="fa fa-tag"></i> Filter by brand <i class="fa fa-chevron-down toggle-icon"></i>
                    </a>
                    <ul :class="'brand menu_opening_height_list'" style="display: none;">
                        <li class="menu_item">
                            <select class="form-control" v-model="filter_brand" name="sort_by_brand" @change="updateBrand()">
                                <option :value="0">All</option>
                                <option v-for="brand in brands" :key="brand.id" v-bind:value="brand.id"> {{ brand.us_brand.title }}</option>
                            </select>
                        </li>
                    </ul>
                </li>

                <li >
                    <a href="#" @click="show_item('sale_type')" class="dropdown-toggle" >
                        <i class="fa fa-shopping-cart"></i> Filter by sale type <i class="fa fa-chevron-down toggle-icon"></i>
                    </a>
                    <ul :class="'sale_type menu_opening_height_list'" style="display: none;">
                        <li class="menu_item">
                            <select class="form-control" v-model="sale_type" name="sort_by_sale_type" @change="updateSaleType()">
                                <option :value="0">All</option>
                                <option :value="'custom_production'">Custom production</option>
                                <option :value="'online_order'">Online sale</option>
                            </select>
                        </li>
                    </ul>
                </li>

                <!-- <li >
                    <a href="#" @click="show_item('min_price')" class="dropdown-toggle" >Filtr by minimal price</a>
                    <ul :class="'min_price menu_opening_height_list'" style="display: none;">
                        <li class="menu_item">
                            <input class='min_price_range price_range width_100' type="range" min="0" max="999" v-model="min_price" step="10">

                            <div class="row">
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
                    <ul :class="'max_price menu_opening_height_list'" style="display: none;">
                        <li class="menu_item">
                            <input class="max_price_range price_range width_100" type="range" min="0" max="999" v-model='max_price' value="1000" step="10">

                            <div class="row ">
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

                <li>
                    <a @click="show_item('price')" class="toggle-btn">
                        <i class="fa fa-dollar"></i> Filter by price <i class="fa fa-chevron-down toggle-icon"></i>
                    </a>
                    <ul class="price" style="display: none;">
                        <li class="menu_item">
                            <input type="number" v-model="min_price" placeholder="Min price" class="form-control">
                        </li>
                        <li class="menu_item">
                            <input type="number" v-model="max_price" placeholder="Max price" class="form-control">
                        </li>
                    </ul>
                </li> -->

            </ul>

            <div class="filter-actions">
                <button @click="applyFilters()" class="btn btn-primary apply-btn">Apply Filters</button>
                <button @click="resetFilters()" class="btn btn-secondary reset-btn">Reset</button>
            </div>
            
        </div>
    </div>
</template>

<script>
    // import { navbar } from '../../../../mixins/navbar_pages_mixin.js'
    export default {
        mixins: [
        //   navbar
        ],
	    name: 'leftMenu',
        data(){
            return {
                menu: false,
                
                min_price: 0,
                max_price: 999,

                filter_category: 0,
                filter_brand: 0,
                sale_type: 0,
                selected_subcategory: 0,
                min_price: '',
                max_price: '',

                categories: [],
                brands: [],
                subcategories: [],
            }
        },
        mounted() {
            this.open_menu()
            this.get_product_categories()
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

            updateBrand(){
                // just update the model
            },

            sort_by_subcategories(subcat_id){
                this.selected_subcategory = subcat_id == 0 ? 0 : subcat_id;
            },

            updateSaleType(){
                // just update the model
            },

            resetFilters(){
                this.filter_brand = 0
                this.sale_type = 0
                this.filter_category = 0
                this.selected_subcategory = 0
                this.min_price = ''
                this.max_price = ''
            },

            applyFilters(){
                const filters = {
                    brand_id: this.filter_brand == 0 ? null : this.filter_brand,
                    sale_type: this.sale_type == 0 ? null : this.sale_type,
                    subcategory_id: this.selected_subcategory == 0 ? null : this.selected_subcategory,
                    price_min: this.min_price ? parseFloat(this.min_price) : null,
                    price_max: this.max_price ? parseFloat(this.max_price) : null
                };
                this.$emit('apply_filters', filters);
                this.open_menu();
            },

            get_product_categories: function(){
                // this.is_loading = true
                axios
                .get("/product_category/")
                .then(response => {
                    this.categories = response.data
                    this.get_product_brabds()
                })
                .catch(
                    error => console.log(error)
                )
                // .finally(() => this.is_loading = false);
            },

            get_product_brabds(){
                axios
                .get("/get_brand/get_all_brands")
                .then(response => {
                    this.brands = response.data
                })
                .catch(
                    error => console.log(error)
                ) 
            },

            get_category_subcategories(){
                if(this.filter_category != 0){
                    axios
                    .get("/subcategory/get_subcategories_for_category/" + this.filter_category)
                    .then(response => {
                        this.subcategories = response.data
                    })
                    .catch(
                        error => console.log(error)
                    )
                    .finally(() => this.is_loading = false);
                }
                else if(this.filter_category == 0){
                    this.sort_by_subcategories(0)
                }
            },

            get_product_price_interval(){
                axios
                .get('../api/products/get_products_price_interval')
                .then(response => {
                    this.max_price = response.data
                })
                .catch(error =>{
                })
            }
        }
    }
</script>

<style scoped>
    /* .menu_opening_list{
        background-color: #04354b; 
        display: none; 
        transition: .4s;
    } */
 .menu_opening_height_list{
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
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 15px;
}

.close-btn {
  background: none;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
}

.toggle-icon {
  float: right;
  transition: transform 0.3s;
}

.filter-actions {
  padding: 20px;
  text-align: center;
}

.apply-btn, .reset-btn {
  margin: 0 10px;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.apply-btn {
  background-color: #007bff;
  color: white;
}

.reset-btn {
  background-color: #6c757d;
  color: white;
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
.menu_small_item{
  display: block;
  height: 100%;
  width: 100%;
  line-height: 35px;
  font-size: 20px;
  color: white;
  padding-right: 20px;
  padding-left: 20px;
  box-sizing: border-box;
  border-bottom: 1px solid black;
  transition: .4s;
}
.menu_small_item:hover{
    background: #084a69;
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