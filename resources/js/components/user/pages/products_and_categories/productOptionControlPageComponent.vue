<template>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-groupe">
                        <!-- <button type='submit' form="myForm" class="btn btn-primary">Save</button> -->
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button class="btn btn-success float-right" @click="go_back()">Go back</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <h1>Product title - <strong>{{ us_product.title }}</strong></h1>
            <p v-if="product.material != null">Material - <strong>{{ product.material }}</strong></p>
            <p v-if="product.discount != null">Discount - <strong>{{ product.discount }} %</strong></p>
            <p v-if="product.mead_in_georgia != null">Mead in georgia - <strong>{{ product.mead_in_georgia }}</strong></p>
            <p>Sale type - <strong>{{ product.sale_type }}</strong></p>
            <!-- <p>Category - <strong>{{ product.product_category.us_name }}</strong></p> -->

            <div class="row">
                <div class="col-md-6">
                    <div class="form-groupe">
                        <router-link class="btn btn-primary" :to="{ name: 'productEdit', params: { id: product.id } }">Edit product</router-link>
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
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>Option id</th>
                                <th>|</th>

                                <th>Option name</th>
                                <th>|</th>

                                <th>Edit</th>
                                <th>|</th>

                                <th>Delite</th>
                            </tr>
                        </thead>

                        <tbody v-if="product_options != []">
                            <tr v-for="option in product_options" :key="option.id">

                                <td>{{ option.id }}</td>
                                <td>|</td>

                                <td>{{ option.name }} </td>
                                <td>|</td>
                                <td>
                                    <button v-if="!is_loading_editing_modal" type="submit" class="btn btn-primary" @click="edit_option_modal(option.id)">Edit</button>
                                    <img v-if="is_loading_editing_modal" :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading" style="width: 20%;">
                                </td>
                                <td>|</td>
                                <td>
                                    <button type="submit" class="btn btn-danger" @click="del_option(option.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <stack-modal
            :show="is_add_option_modal"
            :title="'Add option'" 
            @close="close_option_add_model"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <pre class="language-vue">
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <form v-on:submit.prevent="add_option" id="add_new_option"  v-show="!is_loading">
                    Name
                    <input type="text" v-model="adding_data.name" class="form-control" name="name" placeholder="Name" title="enter your name" required>
                    Price
                    <input type="number" v-model="adding_data.price" class="form-control" name="name" placeholder="Price" title="enter your name" required>
                    Currency
                    <select class="form-control" v-model="adding_data.currency" name="currency"> 
                        <option value="₾">₾</option> 
                        <option value="$" disabled>$</option> 
                        <option value="€" disabled>€</option> 
                    </select> 
                    Quantity
                    <input type="text" v-model="adding_data.quantity" class="form-control" name="name" placeholder="quantity" title="enter your name" required>
                </form>

                <span v-show="!is_loading">
                    <button class="btn btn-primary float-left" @click="add_new_option_image_value()">Add new mount route image</button>
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>|</th>
                                <th>Delite</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="option_image in adding_option_images" :key="option_image.id">
                                <td>
                                    <form ref="myForm">
                                        <input type="file" name="image" id="image" v-on:change="onFileChange($event, option_image.id)">
                                    </form> 
                                </td>
                                <td>|</td>
                                <td>
                                    <button class="btn btn-danger" @click="del_option_image(option_image.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button type="submit" form="add_new_option" class="btn btn-primary">Add new option</button>
                </div>
            </div>
        </stack-modal>


        <stack-modal
            :show="is_edit_option_modal"
            :title="'Edit option'" 
            @close="close_option_edit_model"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <pre class="language-vue">
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <form v-on:submit.prevent="edit_option" id="edit_new_option" v-show="!is_loading">
                    Name
                    <input type="text" v-model="editing_data.name" class="form-control" name="name" placeholder="Name" title="enter your name" required>
                    Price
                    <input type="number" v-model="editing_data.price" class="form-control" name="name" placeholder="Price" title="enter your name" required>
                    Currency
                    <select class="form-control" v-model="editing_data.currency" name="currency"> 
                        <option value="₾">₾</option> 
                        <option value="$" disabled>$</option> 
                        <option value="€" disabled>€</option> 
                    </select> 
                    Quantity
                    <input type="text" v-model="editing_data.quantity" class="form-control" name="name" placeholder="quantity" title="enter your name" required>
                </form>

                <span v-show="!is_loading">
                    <table class="table table-hover" id="dev-table" v-if="editing_option_images != []">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>|</th>
                                <th>Delite</th>
                            </tr>
                        </thead>

                        <tbody >
                            <tr v-for="option_image in editing_option_images" :key="option_image.id">
                                <td>
                                    <!-- <form ref="myForm"> -->
                                        <img class="img-responsive" :src="'../../../../images/product_option_img/'+option_image.image" :alt="option_image.title">
                                    <!-- </form>  -->
                                </td>
                                <td>|</td>
                                <td>
                                <button class="btn btn-danger" @click="del_option_image_from_db(option_image.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                    <button class="btn btn-primary float-left" @click="add_new_option_image_value()">Add new mount route image</button>
                    <table class="table table-hover" id="dev-table" v-if="editing_option_images">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>|</th>
                                <th>Delite</th>
                            </tr>
                        </thead>

                        <tbody >
                            <tr v-for="option_image in adding_option_images" :key="option_image.id">
                                <td>
                                    <form ref="myForm">
                                        <input type="file" name="image" id="image" v-on:change="onFileChange($event, option_image.id)">
                                    </form> 
                                </td>
                                <td>|</td>
                                <td>
                                    <button class="btn btn-danger" @click="del_option_image(option_image.id)">Delete</button>
                                </td>
                            </tr>
                            <!-- <tr v-for="image in adding_option_images" :key="image.id">
                                <td>
                                    <form ref="myForm">
                                        <img class="img-responsive" :src="'../../../../images/product_option_img/'+image.image" :alt="image.title">
                                    </form> 
                                </td>
                                <td>|</td>
                                <td>
                                    <button class="btn btn-danger" >Delete</button>
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button type="submit" form="edit_new_option" class="btn btn-primary">Update option</button>
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            // SlickItem,
            // SlickList,
        },
        props: [
            // 'status',
        ],
        data(){
            return {
                product_options: [],
                product_category: [],
                us_product: [],
                product: [],

                is_add_option_modal: false,

                adding_option_images: [],

                is_loading: false,
                is_loading_editing_modal: false,

                adding_data: {
                    currency: '₾',
                    name: '',
                    price: '',
                    quantity: 10
                },

                is_edit_option_modal: false,

                editing_option_images: [],

                editing_data: {
                    currency: '₾',
                    name: '',
                    price: '',
                    quantity: 10
                },
                editing_option_id: 0,
            }
        },

        mounted() {
            this.get_activ_product_options()
            
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        methods: {
            get_activ_product_options(){
                axios
                .get('/product_option/get_activ_product_options/'+this.$route.params.id)
                .then((response)=> { 
                    this.product_options = response.data.options
                    this.product = response.data.product
                    this.us_product = response.data.us_product
                    this.product_category = response.data.product
                })
                .catch(error =>{
                    // 
                })
            },
            onFileChange(event, item_id){
                let image = event.target.files[0]
                let id = item_id - 1 
                this.adding_option_images[id]['image'] = image
                // this.upload_img()
            },
            del_option_image(id){
                this.removeObjectWithId(this.adding_option_images, id);
            },
            removeObjectWithId(arr, id) {
                const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
                arr.splice(objWithIdIndex, 1);

                return arr;
            },
            get_editing_option(option_id){
                this.is_loading_editing_modal = true

                axios
                .get("/product_option/get_editing_option/"+option_id)
                .then(response => {
                    this.is_edit_option_modal = true

                    this.editing_option_images = response.data.option_images,

                    this.editing_data = {
                        currency: response.data.option.currency,
                        name: response.data.option.name,
                        price: response.data.option.price,
                        quantity: response.data.option.quantity
                    }

                    this.editing_option_id = option_id
                })
                .catch(
                    error => console.log(error)
                )
                .finally(()=>{
                    this.is_loading_editing_modal = false
                });
            },
            edit_option_modal(option_id){
                this.get_editing_option(option_id)
            },
            edit_option(){
                this.is_loading = true

                let formData = new FormData();

                var loop_num = 0
                this.adding_option_images.forEach(image => {
                    formData.append('images['+loop_num+']', image.image)
                    loop_num++
                });
                loop_num = 0

                formData.append('data', JSON.stringify(this.editing_data))
                
                axios
                .post("/product_option/edit_option/"+this.editing_option_id, 
                    formData
                )
                .then(response => {
                    this.close_option_edit_model(true)
                    this.get_activ_product_options()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(()=>{
                    this.is_loading = false
                });
            },
            close_option_edit_model(action = false){
                if(!action){
                    if(confirm('Are you sure, you want close this modal?')){
                        this.is_edit_option_modal = false,

                        this.editing_option_images = [],
                        this.adding_option_images = [],

                        this.editing_data = {
                            currency: '₾',
                            name: '',
                            price: '',
                            quantity: 10
                        }
                        this.editing_option_id = 0
                    }
                }
                else{
                    this.is_edit_option_modal = false,

                    this.editing_option_images = [],
                    this.adding_option_images = [],

                    this.editing_data = {
                        currency: '₾',
                        name: '',
                        price: '',
                        quantity: 10
                    }
                    this.editing_option_id = 0
                }
            },
            del_option(category_id){
                if(confirm('Are you sure, you want delite this option?')){
                    axios
                    .delete("/product_option/del_option/"+category_id)
                    .then(response => {
                        this.get_activ_product_options()
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },
            del_option_image_from_db(image_id){
                if(confirm('Are you sure, you want delite this image?')){
                    axios
                    .delete("/product_option/del_option_image/"+image_id)
                    .then(response => {
                        this.get_editing_option(this.editing_option_id)
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },
            add_option(){
                this.is_loading = true

                let formData = new FormData();

                var loop_num = 0
                this.adding_option_images.forEach(image => {
                    formData.append('images['+loop_num+']', image.image)
                    loop_num++
                });
                loop_num = 0

                formData.append('data', JSON.stringify(this.adding_data))
                formData.append('product_id', this.$route.params.id)
                
                axios
                .post("/product_option/add_option/", 
                    formData
                )
                .then(response => {
                    this.close_option_add_model(true)
                    this.get_activ_product_options()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(()=>{
                    this.is_loading = false
                });
            },
            close_option_add_model(action = false){
                if(!action){
                    if(confirm('Are you sure, you want close this modal?')){
                        this.is_add_option_modal = false
                        this.adding_data = {
                            currency: '₾',
                            name: '',
                            price: '',
                            quantity: 10
                        }
                        this.adding_option_images = []
                    }
                }
                else{
                    this.is_add_option_modal = false
                        this.adding_data = {
                            currency: '₾',
                            name: '',
                            price: '',
                            quantity: 10
                        }
                        this.adding_option_images = []
                }
            },
            new_option_model(){
                this.is_add_option_modal = true
            },
            add_new_option_image_value(){
                var new_item_id = this.adding_option_images.length+1
                this.adding_option_images.push(
                    {
                        id: new_item_id,
                        image: '',
                    }
                );
            },
            go_back(){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.$router.go(-1)
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
