<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ $t('common.id') }}</th>
                    <th>{{ $t('admin.shop.image_col') }}</th>
                    <th>{{ $t('common.name') }}</th>
                    <th>{{ $t('admin.shop.price_label') }}</th>
                    <th>{{ $t('admin.shop.discount_col') }}</th>
                    <th>{{ $t('admin.shop.linked_options_col') }}</th>
                    <th>{{ $t('admin.shop.stock_col') }}</th>
                    <th>{{ $t('common.edit') }}</th>
                    <th>{{ $t('common.delete') }}</th>
                </tr>
            </thead>
            <tbody v-if="combinations && combinations.length > 0">
                <tr v-for="item in combinations" :key="item.combination.id">
                    <td>{{ item.combination.id }}</td>
                    <td>
                        <img v-if="item.images && item.images.length > 0"
                             :src="'../../../../images/product_option_img/' + item.images[0].image"
                             :alt="item.combination.name"
                             style="height: 8em; object-fit: cover; cursor: pointer;"
                             @click="open_image(item.images, 0)">
                        <span v-else class="text-muted">—</span>
                    </td>
                    <td>{{ item.combination.name }}</td>
                    <td>{{ item.combination.price }} {{ item.combination.currency }}</td>
                    <td>{{ item.combination.discount || 0 }}%</td>
                    <td>
                        <span v-if="item.selected_options && item.selected_options.length > 0">
                            <span v-for="opt in item.selected_options" :key="opt.id" class="badge badge-secondary me-1">
                                {{ opt.name }}
                            </span>
                        </span>
                        <span v-else class="text-muted">—</span>
                    </td>
                    <td>
                        <span v-if="item.is_out_of_stock" class="text-danger fw-bold">{{ $t('admin.shop.out_of_stock') }}</span>
                        <span v-else class="text-success">{{ item.stock_quantity }}</span>
                    </td>
                    <td>
                        <button v-if="!is_loading_editing_modal" class="btn btn-primary btn-sm"
                                @click="$emit('edit_combination_modal', item.combination.id)">{{ $t('common.edit') }}</button>
                        <img v-if="is_loading_editing_modal" :src="'/images/site_img/loading.gif'" alt="loading" style="width: 20%;">
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm"
                                @click="$emit('del_combination', item.combination.id)">{{ $t('common.delete') }}</button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr><td colspan="9" class="text-center text-muted">{{ $t('admin.shop.no_combinations_yet') }}</td></tr>
            </tbody>
        </table>

        <Teleport to="body">
            <div class="lb-overlay" v-if="open_img" @click.self="close_image">
                <div class="lb-close" @click="close_image">&#x2715;</div>
                <img :src="'../../../../images/product_option_img/' + active_images[active_index].image"
                     class="lb-img" />
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
        combinations: { type: Array, default: () => [] },
        is_loading_editing_modal: { type: Boolean, default: false }
    },
    data() {
        return { open_img: false, active_images: [], active_index: 0 }
    },
    methods: {
        open_image(images, index) {
            this.active_images = images
            this.active_index = index
            this.open_img = true
            this.add_keyboard_actions()
        },
        close_image() { this.open_img = false; this.active_images = [] },
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
        }
    }
}
</script>

<style scoped>
.table th, .table td { vertical-align: middle; text-align: center; }
.badge-secondary { background: #6c757d; color: #fff; padding: 3px 7px; border-radius: 4px; font-size: 12px; }
.lb-overlay { position: fixed; top:0; right:0; bottom:0; left:0; z-index:9999; background:#000000d9; display:flex; align-items:center; justify-content:center; }
.lb-img { max-width:92%; max-height:85%; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); }
.lb-close { position:absolute; top:14px; right:18px; color:#ccc; font-size:2em; cursor:pointer; z-index:10000; }
.lb-close:hover { color:#fff; }
.lb-nav { position:absolute; top:50%; left:0; right:0; transform:translateY(-50%); display:flex; justify-content:space-between; }
.lb-prev, .lb-next { cursor:pointer; font-size:3em; color:#ccc; padding:0 14px; }
.lb-prev:hover, .lb-next:hover { color:#fff; }
</style>
