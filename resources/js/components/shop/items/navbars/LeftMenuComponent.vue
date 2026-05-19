<template>
    <div :class="'navbar_box'">
        <div class="navbar_ampty_space" @click="open_menu()"></div>
        <div class="sidebar left_sidebar">
            <header>
                <i class="fa fa-filter"></i> Filters
                <button @click="open_menu()" class="close-btn"><i class="fa fa-times"></i></button>
            </header>

            <ul style="padding-left: 0px;">
                <li>
                    <a href="#" @click.prevent="toggleMenu('category')" class="dropdown-toggle">
                        <i class="fa fa-list"></i> Categories <i class="fa fa-chevron-down toggle-icon"></i>
                    </a>
                    <ul :class="'category'" :style="menus.category ? '' : 'display: none;'" style="background-color: #04354b; transition: .4s;">
                        <span>
                            <li class="menu_item" style="height: 104px; padding-top: 35px;" @click.stop>
                                <select class="form-control" v-model="filter_category" @change="get_category_subcategories()" name="sort_by_categories">
                                    <option :value="0">All</option>
                                    <option v-for="category in categories" :key='category.id' :value="category.id">{{ category.us_name }}</option>
                                </select>
                            </li>
                            <li class="menu_item" style="height: 60px; padding-top: 15px;" @click.stop>
                                <select class="form-control" v-model="selected_subcategory" name="sort_by_subcategory">
                                    <option :value="0">All Subcategories</option>
                                    <option v-for="subcat in subcategories" :key="subcat.id" :value="subcat.id">{{ subcat.us_name }}</option>
                                </select>
                            </li>
                        </span>
                    </ul>
                </li>

                <li>
                    <a href="#" @click.prevent="toggleMenu('brand')" class="dropdown-toggle">
                        <i class="fa fa-tag"></i> Filter by brand <i class="fa fa-chevron-down toggle-icon"></i>
                    </a>
                    <ul :class="'brand menu_opening_height_list'" :style="menus.brand ? '' : 'display: none;'">
                        <li class="menu_item" @click.stop>
                            <select class="form-control" v-model="filter_brand" name="sort_by_brand">
                                <option :value="0">All</option>
                                <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.us_brand.title }}</option>
                            </select>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" @click.prevent="toggleMenu('sale_type')" class="dropdown-toggle">
                        <i class="fa fa-shopping-cart"></i> Filter by sale type <i class="fa fa-chevron-down toggle-icon"></i>
                    </a>
                    <ul :class="'sale_type menu_opening_height_list'" :style="menus.sale_type ? '' : 'display: none;'">
                        <li class="menu_item" @click.stop>
                            <select class="form-control" v-model="sale_type" name="sort_by_sale_type">
                                <option :value="0">All</option>
                                <option value="online_order">Online sale</option>
                                <option value="produced_by_order">Produced by order</option>
                                <option value="custom_production">Custom production</option>
                            </select>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" @click.prevent="toggleMenu('price')" class="dropdown-toggle">
                        <i class="fa fa-dollar"></i> Filter by price <i class="fa fa-chevron-down toggle-icon"></i>
                    </a>
                    <ul :class="'price menu_opening_height_list'" :style="menus.price ? '' : 'display: none;'">
                        <li class="menu_item price-filter-item" @click.stop>
                            <div class="price-filter-container">
                                <div class="price-inputs-inline">
                                    <div class="price-input-group">
                                        <label>Min</label>
                                        <input type="number" v-model.number="min_price" min="0" class="form-control price-input">
                                    </div>
                                    <span class="price-separator">-</span>
                                    <div class="price-input-group">
                                        <label>Max</label>
                                        <input type="number" v-model.number="max_price" min="0" class="form-control price-input">
                                    </div>
                                </div>
                                <div class="price-slider-container">
                                    <input type="range" class="price-range-slider" min="0" :max="maxPriceLimit" v-model.number="min_price" step="10">
                                    <input type="range" class="price-range-slider" min="0" :max="maxPriceLimit" v-model.number="max_price" step="10">
                                </div>
                                <div class="price-values">
                                    <span>{{ min_price }}</span>
                                    <span>{{ max_price }}</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="filter-actions">
                <button @click="applyFilters()" class="btn btn-primary apply-btn">Apply Filters</button>
                <button @click="resetFilters()" class="btn btn-secondary reset-btn">Reset</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'leftMenu',
        props: {
            activeFilters: {
                type: Object,
                default: () => ({})
            }
        },
        data() {
            return {
                menu: false,
                min_price: 0,
                max_price: 1000,
                maxPriceLimit: 5000,
                menus: {
                    category: false,
                    brand: false,
                    sale_type: false,
                    price: false
                },
                filter_category: 0,
                filter_brand: 0,
                sale_type: 0,
                selected_subcategory: 0,
                categories: [],
                brands: [],
                subcategories: [],
            }
        },
        mounted() {
            this.open_menu()
            this.get_product_categories()
        },
        watch: {
            activeFilters: {
                handler(filters) {
                    if (!filters) return
                    this.sale_type    = filters.sale_type    || 0
                    this.filter_brand = filters.brand_id     || 0
                    this.selected_subcategory = filters.subcategory_id || 0
                    this.min_price    = filters.price_min    || 0
                    this.max_price    = filters.price_max    || 1000
                    if (filters.subcategory_id && this.subcategories.length === 0) {
                        this.load_subcategories_for_active_brand()
                    }
                },
                immediate: true,
                deep: true
            }
        },
        methods: {
            open_menu() {
                if (this.menu) {
                    this.menu = false
                    document.querySelector('.navbar_box').style.display = ''
                } else {
                    this.menu = true
                    document.querySelector('.navbar_box').style.display = 'none'
                }
            },
            toggleMenu(menuName) {
                this.menus[menuName] = !this.menus[menuName]
            },
            resetFilters() {
                this.filter_brand = 0
                this.sale_type = 0
                this.filter_category = 0
                this.selected_subcategory = 0
                this.min_price = 0
                this.max_price = 1000
            },
            applyFilters() {
                const filters = {
                    brand_id: this.filter_brand == 0 ? null : this.filter_brand,
                    sale_type: this.sale_type == 0 ? null : this.sale_type,
                    subcategory_id: this.selected_subcategory == 0 ? null : this.selected_subcategory,
                    price_min: this.min_price,
                    price_max: this.max_price
                }
                this.$emit('apply-filters', filters)
                Object.keys(this.menus).forEach(key => { this.menus[key] = false })
                this.open_menu()
            },
            get_product_categories() {
                axios.get('/get_product/get_product_category/get_all_product_category')
                    .then(response => {
                        this.categories = response.data
                        this.get_product_brabds()
                    })
                    .catch(error => console.log(error))
            },
            get_product_brabds() {
                axios.get('/get_product/get_brand/get_all_brands')
                    .then(response => {
                        this.brands = response.data
                        this.$emit('brands-loaded', this.brands)
                        // If brand_id is active, make sure it's selected
                        if (this.activeFilters && this.activeFilters.brand_id) {
                            this.filter_brand = this.activeFilters.brand_id
                        }
                        if (this.activeFilters && this.activeFilters.subcategory_id) {
                            this.load_subcategories_for_active_brand()
                        }
                    })
                    .catch(error => console.log(error))
            },
            get_category_subcategories() {
                if (this.filter_category != 0) {
                    axios.get('/get_product/get_product_category/get_subcategory/get_subcategories_for_category/' + this.filter_category)
                        .then(response => {
                            this.subcategories = response.data
                            this.$emit('subcategories-loaded', this.subcategories)
                        })
                        .catch(error => console.log(error))
                } else {
                    this.subcategories = []
                    this.selected_subcategory = 0
                }
            },
            load_subcategories_for_active_brand() {
                // Load all subcategories so active filter can show the name
                axios.get('/get_product/get_product_category/get_subcategory/get_all_subcategories')
                    .then(response => {
                        this.subcategories = response.data
                        this.$emit('subcategories-loaded', this.subcategories)
                    })
                    .catch(() => {})
            }
        }
    }
</script>

<style scoped>
.menu_opening_height_list {
    background-color: rgb(4, 53, 75);
    transition: 0.4s;
    height: auto !important;
    min-height: 150px;
    padding: 10px 15px;
}
.navbar_ampty_space {
    position: fixed;
    z-index: 1091;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    margin-left: 22em;
}
.navbar_box {
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
    width: 22em;
    height: 100%;
    background: #042331;
    transition: all .5s ease;
    z-index: 1090;
    opacity: 1;
}
.left_sidebar {
    overflow: auto;
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
.menu_item {
    display: block;
    width: 100%;
    font-size: 20px;
    color: white;
    padding-right: 20px;
    padding-left: 20px;
    box-sizing: border-box;
    border-bottom: 1px solid black;
    transition: .4s;
}
.menu_item.price-filter-item {
    height: auto !important;
    min-height: 130px;
    padding: 15px 20px;
    line-height: normal !important;
}
.sidebar ul a {
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
ul li:hover a {
    padding-left: 50px;
}
.sidebar ul a i {
    margin-right: 16px;
}
.price-filter-container {
    width: 100%;
    padding: 10px;
}
.price-inputs-inline {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
    gap: 10px;
}
.price-input-group {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.price-input-group label {
    font-size: 12px;
    color: #aaa;
    margin-bottom: 3px;
}
.price-input {
    width: 100%;
    padding: 5px 8px;
    border: 1px solid #444;
    border-radius: 4px;
    background: #1a3a4a;
    color: white;
    font-size: 14px;
}
.price-separator {
    color: white;
    font-weight: bold;
    padding: 0 5px;
}
.price-slider-container {
    position: relative;
    height: 40px;
    margin-bottom: 10px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    padding: 5px 10px;
}
.price-range-slider {
    position: absolute;
    width: calc(100% - 20px);
    left: 10px;
    height: 8px;
    background: transparent;
    border-radius: 4px;
    outline: none;
    -webkit-appearance: none;
    appearance: none;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}
.price-range-slider::-webkit-slider-runnable-track {
    width: 100%;
    height: 8px;
    background: #555;
    border-radius: 4px;
}
.price-range-slider::-moz-range-track {
    width: 100%;
    height: 8px;
    background: #555;
    border-radius: 4px;
}
.price-range-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    background: #fff;
    border: 3px solid #007bff;
    border-radius: 50%;
    cursor: pointer;
    margin-top: -6px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    pointer-events: auto;
    position: relative;
    z-index: 10;
}
.price-range-slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    background: #fff;
    border: 3px solid #007bff;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    pointer-events: auto;
    position: relative;
    z-index: 10;
}
.price-values {
    display: flex;
    justify-content: space-between;
    color: white;
    font-size: 14px;
    font-weight: bold;
}
</style>
