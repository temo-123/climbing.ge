<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-groupe float-right">
                    <button class="btn btn-success" @click="update_data()">{{ $t('common.refresh') }}</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="thumbnail">
                    <img @click="show_add_image_modal()" :alt="$t('head_slider.add_image')" src="images/site_img/function_imgs/add_image.png">
                </div>
            </div>
            <div
                class="col-md-4 mt-3 slide_card"
                v-for="(image, index) in headSliderImages"
                :key="image.id"
                :class="{ is_drag_source: drag_index === index, is_drag_over: drag_over_index === index && drag_index !== index }"
                :draggable="can_reorder"
                @dragstart="on_drag_start(index, $event)"
                @dragover.prevent="drag_over_index = index"
                @drop.prevent="on_drop(index)"
                @dragend="on_drag_end()"
            >
                <div class="thumbnail">
                    <div class="order_bar">
                        <span class="order_number" :title="$t('head_slider.sequence')">
                            <i class="fa fa-arrows" v-if="can_reorder"></i> #{{ index + 1 }}
                        </span>
                        <span class="order_buttons" v-if="can_reorder && headSliderImages.length > 1">
                            <button type="button" class="btn btn-sm btn-outline-secondary" :disabled="index === 0 || is_saving_order" @click="move_slide(index, index - 1)" :title="$t('head_slider.move_earlier')" :aria-label="$t('head_slider.move_earlier')">
                                <i class="fa fa-arrow-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" :disabled="index === headSliderImages.length - 1 || is_saving_order" @click="move_slide(index, index + 1)" :title="$t('head_slider.move_later')" :aria-label="$t('head_slider.move_later')">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </span>
                    </div>
                    <img @click="show_image_modal(image.id)" :alt="image.title" :src="image_path_prop+image.image" class="cursor_pointer" draggable="false">
                    <div class="row">
                        <div class="col-md-12">
                            {{ image.image_type }}
                        </div>
                        <div class="col-md-12" style='color: #f00' v-if="image.published == 0">
                            {{ $t('head_slider.no_public_label') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button @click="show_edit_image_modal(image.id)" type="button" class="btn btn-primary float-left">
                                <i class="fa fa-pencil"></i>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button @click="del_image(image.id)" type="button" class="btn btn-danger float-right">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <edit_image_modal ref="show_edit_image_modal" :image_path_prop="image_path_prop" @update="update_data()"/>
        <show_image_modal ref="show_image_modal" :image_path_prop="image_path_prop"/>

        <add_image_modal ref="show_add_image_modal" :category_prop="category_prop" @update="update_data()" />

    </div>
</template>


<script>
    import add_image_modal from './modals/AddImageModal.vue'
    import edit_image_modal from './modals/EditImageModal.vue'
    import show_image_modal from './modals/ShowImageModal.vue'
    export default {
        data(){
            return {
                headSliderImages: [],
                tab_num: 1,
                drag_index: null,
                drag_over_index: null,
                is_saving_order: false,
            }
        },
        computed: {
            can_reorder() {
                return this.$can('edit', 'head_slider')
            },
        },
        components: {
            add_image_modal,
            edit_image_modal,
            show_image_modal,
        },
        props: [
            'image_path_prop',
            'category_prop'
        ],
        mounted() {
            // this.get_header_images()
        },
        watch: {
            '$route' (to, from) {
                window.scrollTo(0,0)
            },
            images_props: function(){
                this.images_props = this.headSliderImages
            },
        },
        methods: {
            show_add_image_modal(){
                this.$refs.show_add_image_modal.show_modal()
            },
            show_image_modal(id){
                this.$refs.show_image_modal.show_modal(id)
            },
            show_edit_image_modal(id){
                this.$refs.show_edit_image_modal.show_modal(id)
            },
            del_image(slide_id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .delete('/set_head_slider/del_slide/'+slide_id)
                    .then(Response => {
                        this.update_data();
                    })
                    .catch(error => console.log(error))
                }
            },
            update_images(data){
                this.headSliderImages = data
            },

            // --- Slide sequence (per category — this tab only) ---
            on_drag_start(index, event){
                if (!this.can_reorder || this.is_saving_order) return
                this.drag_index = index
                event.dataTransfer.effectAllowed = 'move'
            },
            on_drop(index){
                if (this.drag_index !== null && this.drag_index !== index) {
                    this.move_slide(this.drag_index, index)
                }
                this.on_drag_end()
            },
            on_drag_end(){
                this.drag_index = null
                this.drag_over_index = null
            },
            move_slide(from, to){
                if (to < 0 || to >= this.headSliderImages.length || from === to) return
                const previous = [...this.headSliderImages]
                const list = [...this.headSliderImages]
                const [moved] = list.splice(from, 1)
                list.splice(to, 0, moved)
                this.headSliderImages = list
                this.save_order(previous)
            },
            save_order(previous){
                this.is_saving_order = true
                axios
                .post('/set_head_slider/reorder_slides', {
                    category: this.category_prop,
                    ids: this.headSliderImages.map(image => image.id),
                })
                .then(() => {
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('head_slider.order_saved') })
                    this.update_data()
                })
                .catch(error => {
                    this.headSliderImages = previous
                    if (error.response && error.response.status === 422) {
                        this.$bus.$emit('toast', { type: 'danger', message: this.$t('head_slider.order_save_failed') })
                        this.update_data()
                    }
                })
                .finally(() => this.is_saving_order = false)
            },
            update_data(){
                this.$emit("get_all_images");
            }
        }
    }
</script>

<style scoped>
.slide_card[draggable="true"] {
    cursor: grab;
}

.slide_card.is_drag_source {
    opacity: 0.45;
}

.slide_card.is_drag_over .thumbnail {
    outline: 3px dashed #0d6efd;
    outline-offset: 2px;
}

.order_bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
}

.order_number {
    font-weight: 700;
    font-size: 1.05rem;
}

.order_number .fa {
    color: #999;
    margin-right: 4px;
}

.order_buttons {
    display: flex;
    gap: 4px;
}
</style>
