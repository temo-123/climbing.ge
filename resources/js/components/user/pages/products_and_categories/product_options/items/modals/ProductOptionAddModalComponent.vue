<template>
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
                    <input type="text" v-model="local_adding_data.name" class="form-control" name="name" placeholder="Name" title="enter your name" required>
                    Price
                    <input type="number" v-model="local_adding_data.price" class="form-control" name="price" placeholder="Price" title="enter price" required>
                    Currency
                    <select class="form-control" v-model="local_adding_data.currency" name="currency">
                        <option value="₾">₾</option>
                        <option value="$" disabled>$</option>
                        <option value="€" disabled>€</option>
                    </select>
                    Discount (%)
                    <input type="number" max="100" min="0" v-model="local_adding_data.discount" class="form-control" name="discount" placeholder="Discount" title="enter discount">
                </form>

                <div v-show="!is_loading">
                    <button class="btn btn-primary float-left" @click="add_new_option_image_value()">Add new mount route image</button>
                    <div v-if="adding_option_images.length > 0">
                        <table class="table table-hover" id="dev-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
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
                    </div> <div v-else>
                        <p class="text-muted mt-2">No images added yet</p>
                    </div>
                </div>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button type="submit" form="add_new_option" class="btn btn-primary">Add new option</button>
                </div>
            </div>
        </stack-modal>
</template>

<script>
// import StackModal from '@innologica/vue-stackable-modal'  // Global now

export default {
    components: {
        StackModal
    },
    props: {
        is_add_option_modal: {
            type: Boolean,
            required: true
        },
        adding_data: {
            type: Object,
            default: () => ({
                name: '',
                price: 0,
                currency: '₾',
                discount: 0
            })
        },
        adding_option_images: {
            type: Array,
            default: () => []
        },
        is_loading: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        local_adding_data: {
            get() {
                return this.adding_data;
            }
        }
    },
    methods: {
        close_option_add_model() {
            this.$emit('close_option_add_model')
        },
        add_new_option_image_value() {
            this.$emit('add_new_option_image_value')
        },
        onFileChange(event, imageId) {
            this.$emit('onFileChange', event, imageId)
        },
        del_option_image(imageId) {
            this.$emit('del_option_image', imageId)
        },
        add_option() {
            this.$emit('add_option')
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
</style>

