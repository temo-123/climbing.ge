<template>
        <div class="col-md-12">
            <div v-if="is_loading_product" class="row justify-content-center">
                <div class="col-md-4">
                    <img :src="'/images/site_img/loading.gif'" alt="loading">
                </div>
            </div>

            <div v-else>
                <h1 v-if="product && product.title">{{ $t('admin.shop.product_title_prefix') }} <strong>{{ product.title }}</strong></h1>
                <p v-if="product && product.material != null">{{ $t('admin.shop.material_prefix') }} <strong>{{ product.material }}</strong></p>
                <p v-if="product && product.discount != null">{{ $t('admin.shop.discount_prefix') }} <strong>{{ product.discount }} %</strong></p>
                <p v-if="product && product.mead_in_georgia != null">{{ $t('admin.shop.made_in_georgia_prefix') }} <strong>{{ product.mead_in_georgia }}</strong></p>
                <p v-if="product && product.sale_type">{{ $t('admin.shop.sale_type_prefix') }} <strong>{{ product.sale_type }}</strong></p>
                <!-- <p>Category - <strong>{{ product.product_category.us_name }}</strong></p> -->

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-groupe">
                            <router-link v-if="product && product.id" class="btn btn-primary" :to="{ name: 'productEdit', params: { id: product.id } }">{{ $t('admin.shop.edit_product_link') }}</router-link>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <hr />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-groupe">
                            <button class="btn btn-primary" @click="new_option_model()">{{ $t('admin.shop.add_new_option') }}</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-groupe float-right">
                            <button class="btn btn-success" @click="get_activ_product_options()">{{ $t('common.refresh') }}</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <product-options-table-component 
                            :product_options="product_options"
                            :is_loading_editing_modal="is_loading_editing_modal"
                            @edit_option_modal="edit_option_modal"
                            @del_option="del_option"
                        ></product-options-table-component>
                    </div>
                </div>
            </div>
        
            <product-option-add-modal-component
                :is_add_option_modal="is_add_option_modal"
                :adding_data="adding_data"
                :is_loading="is_loading"
                @close_option_add_model="close_option_add_model"
                @update_adding_images="adding_option_images = $event"
                @add_option="add_option"
            ></product-option-add-modal-component>

            <product-option-edit-modal-component
                :is_edit_option_modal="is_edit_option_modal"
                :editing_data="editing_data"
                :is_loading="is_loading"
                :is_loading_editing_modal="is_loading_editing_modal"
                @close_option_edit_model="close_option_edit_model"
                @update_adding_images="adding_option_images = $event"
                @edit_option="edit_option"
            ></product-option-edit-modal-component>
            
        </div>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  // Global now
    import axios from 'axios'
    import productOptionsTableComponent from './ProductOptionsTableComponent.vue'
    import productOptionAddModalComponent from './modals/ProductOptionAddModalComponent.vue'
    import productOptionEditModalComponent from './modals/ProductOptionEditModalComponent.vue'
    
    export default {
        components: {
            productOptionsTableComponent,
            productOptionAddModalComponent,
            productOptionEditModalComponent
        },
        data() {
            return {
                product: {},
                product_id: null,
                product_options: [],
                is_add_option_modal: false,
                is_edit_option_modal: false,
                is_loading: false,
                is_loading_product: false,
                is_loading_editing_modal: false,
                adding_data: {
                    name: '',
                    price: 0,
                    currency: '₾',
                    discount: 0,
                    barcode: ''
                },
                editing_data: {},
                adding_option_images: []
            }
        },
        mounted() {
            this.product_id = this.$route.params.id;
            if (this.product_id) {
                this.get_product();
                this.get_activ_product_options();
            }
        },
        methods: {
            go_back() {
                this.$router.go(-1);
            },
            new_option_model() {
                this.is_add_option_modal = true;
            },
            close_option_add_model() {
                this.is_add_option_modal = false;
                this.adding_data = {
                    name: '',
                    price: 0,
                    currency: '₾',
                    discount: 0,
                    barcode: ''
                };
                this.adding_option_images = [];
            },
            add_option() {
                this.save_option();
            },
            edit_option_modal(option_id) {
                this.is_loading_editing_modal = true;
                this.open_edit_modal(option_id);
            },
            close_option_edit_model() {
                this.is_edit_option_modal = false;
                this.editing_data = {};
                this.adding_option_images = [];
            },
            edit_option() {
                this.update_option();
            },
            del_option(option_id) {
                if (confirm(this.$t('admin.shop.confirm_delete_option'))) {
                    this.delete_option(option_id);
                }
            },
            // API Methods
            async get_product() {
                this.is_loading_product = true;
                try {
                    const response = await axios.get('/set_product/get_product_editing_data/' + this.product_id);
                    this.product = response.data.product;
                } catch (error) {
                    console.error('Error fetching product:', error);
                    alert(this.$t('admin.shop.failed_load_product_data'));
                } finally {
                    this.is_loading_product = false;
                }
            },
            
            async get_activ_product_options() {
                try {
                    const response = await axios.get('/set_product/set_product_option/get_product_options_for_editing/' + this.product_id);
                    this.product_options = response.data.options;
                } catch (error) {
                    console.error('Error fetching product options:', error);
                    alert(this.$t('admin.shop.failed_load_product_options'));
                }
            },
            
            async open_edit_modal(option_id) {
                try {
                    const response = await axios.get('/set_product/set_product_option/get_editing_product_option/' + option_id);
                    this.editing_data = response.data.option;
                    this.is_edit_option_modal = true;
                } catch (error) {
                    console.error('Error fetching option for edit:', error);
                    alert(this.$t('admin.shop.failed_load_option_data'));
                } finally {
                    this.is_loading_editing_modal = false;
                }
            },
            
            async save_option() {
                this.is_loading = true;
                try {
                    const formData = new FormData();
                    formData.append('product_id', this.product_id);
                    formData.append('data', JSON.stringify(this.adding_data));
                    
                    // Append images
                    this.adding_option_images.forEach((img, index) => {
                        if (img.image) {
                            formData.append('images[' + index + ']', img.image);
                        }
                    });
                    
                    await axios.post('/set_product/set_product_option/add_option', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    
                    this.close_option_add_model();
                    await this.get_activ_product_options();
                    alert(this.$t('admin.shop.option_added_success'));
                } catch (error) {
                    console.error('Error adding option:', error);
                    alert(this.$t('admin.shop.failed_add_option'));
                } finally {
                    this.is_loading = false;
                }
            },
            
            async update_option() {
                this.is_loading = true;
                try {
                    const formData = new FormData();
                    formData.append('data', JSON.stringify(this.editing_data));
                    
                    // Append new images
                    this.adding_option_images.forEach((img, index) => {
                        if (img.image) {
                            formData.append('images[' + index + ']', img.image);
                        }
                    });
                    
                    await axios.post('/set_product/set_product_option/edit_option/' + this.editing_data.id, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    
                    this.close_option_edit_model();
                    await this.get_activ_product_options();
                    alert(this.$t('admin.shop.option_updated_success'));
                } catch (error) {
                    console.error('Error updating option:', error);
                    alert(this.$t('admin.shop.failed_update_option'));
                } finally {
                    this.is_loading = false;
                }
            },
            
            async delete_option(option_id) {
                try {
                    await axios.delete('/set_product/set_product_option/del_option/' + option_id);
                    await this.get_activ_product_options();
                    alert(this.$t('admin.shop.option_deleted_success'));
                } catch (error) {
                    console.error('Error deleting option:', error);
                    alert(this.$t('admin.shop.failed_delete_option'));
                }
            }
        }
    }
</script>

<style scoped>
/* table {
  width: 100%;
}

td {
  height: 70px;
} */
</style>

