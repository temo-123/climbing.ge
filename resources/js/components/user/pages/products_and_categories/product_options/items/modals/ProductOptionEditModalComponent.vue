<template>
<stack-modal
            :show="is_edit_option_modal"
            :title="'Edit option'" 
            @close="close_option_edit_model"
            @save="edit_option"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <div>
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <form v-on:submit.prevent="edit_option" id="edit_new_option" v-show="!is_loading">
                    Name
                    <input type="text" v-model="local_editing_data.name" class="form-control" name="name" placeholder="Name" title="enter your name" required>
                    Price
                    <input type="number" v-model="local_editing_data.price" class="form-control" name="price" placeholder="Price" title="enter price" required>
                    Currency
                    <select class="form-control" v-model="local_editing_data.currency" name="currency">
                        <option value="₾">₾</option>
                        <option value="$" disabled>$</option>
                        <option value="€" disabled>€</option>
                    </select>
                    Discount (%)
                    <input type="number" max="100" min="0" v-model="local_editing_data.discount" class="form-control" name="discount" placeholder="Discount" title="enter discount">
                </form>

                <div v-show="!is_loading">
                    <div v-if="editing_option_images && editing_option_images.length > 0">
                        <table class="table table-hover" id="dev-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>|</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="option_image in editing_option_images" :key="option_image.id">
                                    <td>
                                        <img class="img-responsive" :src="'../../../../images/product_option_img/'+option_image.image" :alt="option_image.title" style="max-width: 100px; max-height: 100px;">
                                    </td>
                                    <td>|</td>
                                    <td>
                                        <button class="btn btn-danger" @click="del_option_image_from_db(option_image.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-primary float-left" @click="add_new_option_image_value()">Add new image</button>
                    <div v-if="adding_option_images.length > 0">
                        <table class="table table-hover" id="dev-table" style="margin-top: 50px;">
                            <thead>
                                <tr>
                                    <th>New Image</th>
                                    <th>|</th>
                                    <th>Delete</th>
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
                    </div>
                    <div v-if="editing_option_images.length === 0 && adding_option_images.length === 0">
                        <p class="text-muted mt-2">No images available</p>
                    </div>
                </div>
            </div>
            <!-- <div slot="modal-footer">
                <div class="modal-footer">
                    <button type="submit" form="edit_new_option" class="btn btn-primary">Update option</button>
                </div>
            </div> -->
        </stack-modal>
</template>

<script>
// import StackModal from '@innologica/vue-stackable-modal'  // Global now

export default {
    components: {
        // StackModal
    },
    props: {
        is_edit_option_modal: {
            type: Boolean,
            required: true
        },
        editing_data: {
            type: Object,
            default: () => ({})
        },
        is_loading: {
            type: Boolean,
            default: false
        },
        is_loading_editing_modal: {
            type: Boolean,
            default: false
        },
        editing_option_images: {
            type: Array,
            default: () => []
        },
        adding_option_images: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        local_editing_data: {
            get() {
                return this.editing_data;
            }
        }
    },
    methods: {
        close_option_edit_model() {
            this.$emit('close_option_edit_model')
        },
        edit_option() {
            this.$emit('edit_option')
        },
        del_option_image_from_db(image_id) {
            this.$emit('del_option_image_from_db', image_id)
        },
        add_new_option_image_value() {
            this.$emit('add_new_option_image_value')
        },
        del_option_image(image_id) {
            this.$emit('del_option_image', image_id)
        },
        onFileChange(event, image_id) {
            this.$emit('onFileChange', event, image_id)
        }
    }
}
</script>

<style scoped>
.language-vue {
    padding: 15px;
}
.form-control {
    margin-bottom: 15px;
}
.table {
    margin-top: 15px;
}
.table th {
    background-color: #f5f5f5;
    font-weight: 600;
    text-align: center;
}
.table td {
    vertical-align: middle;
    text-align: center;
}
.float-left {
    float: left;
}
.modal-footer {
    width: 100%;
}
.img-responsive {
    max-width: 100px;
    max-height: 100px;
    object-fit: cover;
}
</style>

