<template>
        <div class="col-md-12">
            <div v-if="is_loading_product" class="row justify-content-center">
                <div class="col-md-4">
                    <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                </div>
            </div>

            <div v-else>
                <h1 v-if="product && product.title">Product title - <strong>{{ product.title }}</strong></h1>
                <p v-if="product && product.material != null">Material - <strong>{{ product.material }}</strong></p>
                <p v-if="product && product.discount != null">Discount - <strong>{{ product.discount }} %</strong></p>
                <p v-if="product && product.mead_in_georgia != null">Mead in georgia - <strong>{{ product.mead_in_georgia }}</strong></p>
                <p v-if="product && product.sale_type">Sale type - <strong>{{ product.sale_type }}</strong></p>
                <!-- <p>Category - <strong>{{ product.product_category.us_name }}</strong></p> -->

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-groupe">
                            <router-link v-if="product && product.id" class="btn btn-primary" :to="{ name: 'productEdit', params: { id: product.id } }">Edit product</router-link>
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
                            <button class="btn btn-primary" @click="new_option_model()">Add new option</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-groupe float-right">
                            <button class="btn btn-success" @click="get_activ_product_options()">Refresh</button>
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
                :adding_option_images="adding_option_images"
                :is_loading="is_loading"
                @close_option_add_model="close_option_add_model"
                @onFileChange="onFileChange"
                @del_option_image="del_option_image"
                @add_option="add_option"
                @add_new_option_image_value="add_new_option_image_value"
            ></product-option-add-modal-component>

            <product-option-edit-modal-component 
                :is_edit_option_modal="is_edit_option_modal"
                :editing_data="editing_data"
                :editing_option_images="editing_option_images"
                :adding_option_images="adding_option_images"
                :is_loading="is_loading"
                :is_loading_editing_modal="is_loading_editing_modal"
                @close_option_edit_model="close_option_edit_model"
                @onFileChange="onFileChange"
                @del_option_image_from_db="del_option_image_from_db"
                @del_option_image="del_option_image"
                @add_new_option_image_value="add_new_option_image_value"
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
            StackModal,
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
                    discount: 0
                },
                editing_data: {},
                adding_option_images: [],
                editing_option_images: []
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
                    discount: 0
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
                this.editing_option_images = [];
                this.adding_option_images = [];
            },
            edit_option() {
                this.update_option();
            },
            del_option(option_id) {
                if (confirm('Are you sure you want to delete this option?')) {
                    this.delete_option(option_id);
                }
            },
            del_option_image_from_db(image_id) {
                this.delete_option_image(image_id);
            },
            add_new_option_image_value() {
                const newImage = {
                    id: Date.now(),
                    file: null
                };
                this.adding_option_images.push(newImage);
            },
            del_option_image(image_id) {
                this.adding_option_images = this.adding_option_images.filter(img => img.id !== image_id);
            },
            onFileChange(event, imageId) {
                const file = event.target.files[0];
                const image = this.adding_option_images.find(img => img.id === imageId);
                if (image) {
                    image.file = file;
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
                    alert('Failed to load product data');
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
                    alert('Failed to load product options');
                }
            },
            
            async open_edit_modal(option_id) {
                try {
                    const response = await axios.get('/set_product/set_product_option/get_editing_product_option/' + option_id);
                    this.editing_data = response.data.option;
                    this.editing_option_images = response.data.option_images || [];
                    this.is_edit_option_modal = true;
                } catch (error) {
                    console.error('Error fetching option for edit:', error);
                    alert('Failed to load option data');
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
                        if (img.file) {
                            formData.append('images[' + index + ']', img.file);
                        }
                    });
                    
                    await axios.post('/set_product/set_product_option/add_option', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    
                    this.close_option_add_model();
                    await this.get_activ_product_options();
                    alert('Option added successfully');
                } catch (error) {
                    console.error('Error adding option:', error);
                    alert('Failed to add option');
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
                        if (img.file) {
                            formData.append('images[' + index + ']', img.file);
                        }
                    });
                    
                    await axios.post('/set_product/set_product_option/edit_option/' + this.editing_data.id, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    
                    this.close_option_edit_model();
                    await this.get_activ_product_options();
                    alert('Option updated successfully');
                } catch (error) {
                    console.error('Error updating option:', error);
                    alert('Failed to update option');
                } finally {
                    this.is_loading = false;
                }
            },
            
            async delete_option(option_id) {
                try {
                    await axios.delete('/set_product/set_product_option/del_option/' + option_id);
                    await this.get_activ_product_options();
                    alert('Option deleted successfully');
                } catch (error) {
                    console.error('Error deleting option:', error);
                    alert('Failed to delete option');
                }
            },
            
            async delete_option_image(image_id) {
                try {
                    await axios.delete('/set_product/set_product_option/del_option_image/' + image_id);
                    // Refresh edit modal data if open
                    if (this.is_edit_option_modal && this.editing_data.id) {
                        await this.open_edit_modal(this.editing_data.id);
                    }
                    alert('Image deleted successfully');
                } catch (error) {
                    console.error('Error deleting image:', error);
                    alert('Failed to delete image');
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

