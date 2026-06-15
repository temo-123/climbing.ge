<template>
    <div>
    <table class="table table-hover" id="dev-table">
        <thead>
            <tr>
                <th>Option id</th>
                <th>image</th>
                <th>Option name</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>

        <tbody v-if="product_options && product_options.length > 0">
            <tr v-for="option in product_options" :key="option.id">

                <td>{{ option.id }}</td>
                <td>
                    <img v-if="option.images && option.images.length > 0"
                            :src="'../../../../images/product_option_img/' + option.images[0].image"
                            :alt="option.name"
                            style="height: 8em; object-fit: cover; cursor: pointer;"
                            @click="open_image(option.images, 0)">
                    <span v-else>No image</span>
                </td>
                <td>{{ option.name }} </td>
                <td>{{ option.price }} </td>
                <td>{{ option.discount }} </td>

                <td>
                    <button v-if="!is_loading_editing_modal" type="submit" class="btn btn-primary" @click="$emit('edit_option_modal', option.id)">Edit</button>
                    <img v-if="is_loading_editing_modal" :src="'/images/site_img/loading.gif'" alt="loading" style="width: 20%;">
                </td>
                <td>
                    <button type="submit" class="btn btn-danger" @click="$emit('del_option', option.id)">Delete</button>
                </td>
            </tr>
        </tbody>
        <tbody v-else>
            <tr>
                <td colspan="7" class="text-center">No options available</td>
            </tr>
        </tbody>
    </table>

    <Teleport to="body">
        <div class="lb-overlay" v-if="open_img" @click.self="close_image">
            <div class="lb-close" @click="close_image">&#x2715;</div>
            <img :src="'../../../../images/product_option_img/' + active_images[active_index].image" :alt="active_images[active_index].title || ''" class="lb-img" />
            <div class="lb-nav" v-if="active_images.length > 1">
                <div class="lb-prev" @click="prev_image"><i class="fa fa-chevron-left"></i></div>
                <div class="lb-next" @click="next_image"><i class="fa fa-chevron-right"></i></div>
            </div>
        </div>
    </Teleport>
    </div>
</template>

<script>
export default {
    props: {
        product_options: {
            type: Array,
            default: () => []
        },
        is_loading_editing_modal: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            open_img: false,
            active_images: [],
            active_index: 0,
        }
    },
    methods: {
        open_image(images, index) {
            this.active_images = images
            this.active_index = index
            this.open_img = true
            this.add_keyboard_actions()
        },
        close_image() {
            this.open_img = false
            this.active_images = []
        },
        prev_image() {
            this.active_index = this.active_index === 0 ? this.active_images.length - 1 : this.active_index - 1
            this.add_keyboard_actions()
        },
        next_image() {
            this.active_index = this.active_index === this.active_images.length - 1 ? 0 : this.active_index + 1
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

.product-options-table {
    margin-top: 20px;
}

.table {
    margin-bottom: 0;
}

.table th {
    /* background-color: #f5f5f5; */
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
}

.table td {
    vertical-align: middle;
    text-align: center;
}

.table td:first-child {
    font-weight: 600;
}

.price-display {
    font-weight: 600;
    color: #333;
}

.badge {
    padding: 5px 10px;
    font-size: 12px;
}

.btn-group {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}

.image-preview-mini {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.alert-summary {
    margin-top: 15px;
    margin-bottom: 15px;
    padding: 10px 15px;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
}

.alert-info {
    margin: 20px 0;
    padding: 20px;
}

.pull-right {
    float: right;
}

.text-success {
    color: #28a745;
}

.text-muted {
    color: #6c757d;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 12px;
    }
    
    .btn-sm {
        padding: 3px 6px;
        font-size: 10px;
    }
}
</style>

