<template>
<div>
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
                        <img :src="'/images/site_img/loading.gif'" alt="loading">
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
                    Barcode
                    <div v-if="!local_editing_data.barcode" class="barcode-empty-state">
                        <div class="barcode-actions">
                            <input type="text" v-model="barcode_input" class="form-control barcode-input" placeholder="Scan barcode here..." @keydown.enter.prevent="set_barcode_from_input">
                            <button type="button" class="btn btn-outline-secondary" @click="set_barcode_from_input" :disabled="!barcode_input">
                                <i class="fa fa-check"></i> Set
                            </button>
                            <button type="button" class="btn btn-outline-primary" @click="generate_barcode">
                                <i class="fa fa-barcode"></i> Generate
                            </button>
                        </div>
                    </div>
                    <div v-else class="barcode-set-state">
                        <div class="barcode-display">
                            <span class="barcode-value">{{ local_editing_data.barcode }}</span>
                            <button type="button" class="btn btn-sm btn-outline-danger" @click="clear_barcode">
                                <i class="fa fa-times"></i> Clear
                            </button>
                        </div>
                        <barcode-image :code="local_editing_data.barcode" />
                    </div>
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
                                <tr v-for="(option_image, idx) in editing_option_images" :key="option_image.id">
                                    <td>
                                        <img class="img-responsive" :src="'../../../../images/product_option_img/'+option_image.image" :alt="option_image.title" style="max-width: 100px; max-height: 100px; cursor: pointer;" @click="open_image(idx)">
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

    <Teleport to="body">
        <div class="lb-overlay" v-if="open_img" @click.self="close_image">
            <div class="lb-close" @click="close_image">&#x2715;</div>
            <img :src="'../../../../images/product_option_img/' + editing_option_images[active_index].image" :alt="editing_option_images[active_index].title || ''" class="lb-img" />
            <div class="lb-nav" v-if="editing_option_images.length > 1">
                <div class="lb-prev" @click="prev_image"><i class="fa fa-chevron-left"></i></div>
                <div class="lb-next" @click="next_image"><i class="fa fa-chevron-right"></i></div>
            </div>
        </div>
    </Teleport>
</div>
</template>

<script>
// import StackModal from '@innologica/vue-stackable-modal'  // Global now
import BarcodeImage from '../BarcodeImageComponent.vue'

export default {
    components: {
        BarcodeImage
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
    data() {
        return {
            open_img: false,
            active_index: 0,
            barcode_input: ''
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
        generate_barcode() {
            let digits = '';
            for (let i = 0; i < 12; i++) {
                digits += Math.floor(Math.random() * 10).toString();
            }
            let sum = 0;
            for (let i = 0; i < 12; i++) {
                sum += parseInt(digits[i]) * (i % 2 === 0 ? 1 : 3);
            }
            const check = (10 - (sum % 10)) % 10;
            this.local_editing_data.barcode = digits + check;
            this.barcode_input = '';
        },
        set_barcode_from_input() {
            if (this.barcode_input.trim()) {
                this.local_editing_data.barcode = this.barcode_input.trim();
                this.barcode_input = '';
            }
        },
        clear_barcode() {
            this.local_editing_data.barcode = '';
            this.barcode_input = '';
        },
        open_image(index) {
            this.active_index = index
            this.open_img = true
            this.add_keyboard_actions()
        },
        close_image() {
            this.open_img = false
        },
        prev_image() {
            this.active_index = this.active_index === 0 ? this.editing_option_images.length - 1 : this.active_index - 1
            this.add_keyboard_actions()
        },
        next_image() {
            this.active_index = this.active_index === this.editing_option_images.length - 1 ? 0 : this.active_index + 1
            this.add_keyboard_actions()
        },
        add_keyboard_actions() {
            const that = this
            document.addEventListener('keydown', function handler(evt) {
                if (evt.keyCode === 27) that.close_image()
                else if (evt.keyCode === 37) that.prev_image()
                else if (evt.keyCode === 39) that.next_image()
                document.removeEventListener('keydown', handler)
            }, { once: true })
        },
        close_option_edit_model() {
            this.barcode_input = '';
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
.lb-overlay {
    position: fixed; top: 0; right: 0; bottom: 0; left: 0;
    z-index: 9999; background: #000000d9;
    display: flex; align-items: center; justify-content: center;
}
.lb-img {
    max-width: 92%; max-height: 85%;
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
}
.lb-close {
    position: absolute; top: 14px; right: 18px;
    color: #ccc; font-size: 2em; cursor: pointer; z-index: 10000;
}
.lb-close:hover { color: #fff; }
.lb-nav {
    position: absolute; top: 50%; left: 0; right: 0;
    transform: translateY(-50%);
    display: flex; justify-content: space-between;
}
.lb-prev, .lb-next {
    cursor: pointer; font-size: 3em; color: #ccc; padding: 0 14px;
}
.lb-prev:hover, .lb-next:hover { color: #fff; }

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

.barcode-empty-state { margin-bottom: 15px; }
.barcode-actions {
    display: flex;
    gap: 6px;
    align-items: center;
}
.barcode-input { margin-bottom: 0; flex: 1; }
.barcode-set-state { margin-bottom: 15px; }
.barcode-display {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f0f4ff;
    border: 1px solid #c0cfff;
    border-radius: 6px;
    padding: 8px 12px;
    margin-bottom: 6px;
}
.barcode-value {
    font-family: monospace;
    font-size: 15px;
    font-weight: 600;
    color: #2c4a9e;
    flex: 1;
}
</style>

