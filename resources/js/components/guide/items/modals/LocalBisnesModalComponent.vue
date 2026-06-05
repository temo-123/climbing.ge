<template>
    <div>
        <StackModal
            v-model="show"
            :title="bisnes_data?.locale_data?.title || ''"
            :size="'xl'"
            :save-button="false"
            @close="close"
        >
            <div v-if="loading" class="text-center p-4">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
            </div>

            <div v-else-if="bisnes_data" class="bisnes-modal-body">
                <div v-if="bisnes_data.locale_data?.text" v-html="bisnes_data.locale_data.text"></div>

                <div v-if="bisnes_data.images && bisnes_data.images.length > 0" class="bisnes-gallery mt-3">
                    <h4>{{ $t('gallery') || 'Gallery' }}</h4>
                    <div class="bisnes-gallery-grid">
                        <div
                            v-for="(img, index) in bisnes_data.images"
                            :key="img.id"
                            class="bisnes-gallery-item"
                            @click="open_image(img, index)"
                        >
                            <img
                                :src="'/public/images/suport_local_bisnes_img/' + img.image"
                                :alt="bisnes_data.locale_data.title"
                            />
                        </div>
                    </div>
                </div>

                <!-- Lightbox -->
                <div class="open_img" v-if="open_img" @click.self="close_image">
                    <div class="close_bottom" @click="close_image">X</div>
                    <img
                        :src="'/public/images/suport_local_bisnes_img/' + active_img.image"
                        :alt="bisnes_data.locale_data.title"
                    />
                    <div class="image_moving">
                        <div class="previes_img_bottom" @click="prev_image">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </div>
                        <div class="next_img_bottom" @click="next_image">
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </StackModal>
    </div>
</template>

<script>
    export default {
        name: 'LocalBisnesModalComponent',
        props: {
            urlTitle: {
                type: String,
                required: true
            },
            modelValue: {
                type: Boolean,
                default: false
            }
        },
        emits: ['update:modelValue'],
        data() {
            return {
                bisnes_data: null,
                loading: false,
                open_img: false,
                active_img: null,
                active_index: 0,
            }
        },
        computed: {
            show: {
                get() { return this.modelValue },
                set(val) { this.$emit('update:modelValue', val) }
            }
        },
        mounted() {
            if (this.modelValue) {
                this.fetch()
            }
        },
        watch: {
            urlTitle(newVal, oldVal) {
                if (newVal && newVal !== oldVal) {
                    this.bisnes_data = null
                    this.fetch()
                }
            },
            modelValue(val) {
                if (val && !this.bisnes_data) {
                    this.fetch()
                }
            }
        },
        methods: {
            fetch() {
                this.loading = true
                axios
                    .get('/get_bisnes/get_local_bisnes_in_page/' + this.urlTitle + '/' + (localStorage.getItem('lang') || 'en'))
                    .then(response => {
                        this.bisnes_data = response.data
                    })
                    .catch(() => {})
                    .finally(() => this.loading = false)
            },
            close() {
                this.close_image()
                this.show = false
            },
            open_image(img, index) {
                this.active_img = img
                this.active_index = index
                this.open_img = true
                this.add_keyboard_actions()
            },
            close_image() {
                this.open_img = false
                this.active_img = null
            },
            prev_image() {
                const images = this.bisnes_data.images
                this.active_index = this.active_index === 0 ? images.length - 1 : this.active_index - 1
                this.active_img = images[this.active_index]
                this.add_keyboard_actions()
            },
            next_image() {
                const images = this.bisnes_data.images
                this.active_index = this.active_index === images.length - 1 ? 0 : this.active_index + 1
                this.active_img = images[this.active_index]
                this.add_keyboard_actions()
            },
            add_keyboard_actions() {
                const that = this
                document.addEventListener('keydown', function handler(evt) {
                    if (evt.keyCode === 27) { that.close_image() }
                    else if (evt.keyCode === 37) { that.prev_image() }
                    else if (evt.keyCode === 39) { that.next_image() }
                    document.removeEventListener('keydown', handler)
                }, { once: true })
            },
        }
    }
</script>

<style scoped>
    .bisnes-modal-body {
        padding: 8px 0;
    }

    .bisnes-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }

    .bisnes-gallery-item img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
    }

    .open_img {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 2000;
        background: #000000d9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .open_img img {
        max-width: 96%;
        max-height: 80%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .close_bottom {
        position: absolute;
        top: 0;
        right: 0;
        cursor: pointer;
        color: #b3b2b2d9;
        font-size: 2.5em;
        margin-right: 0.4em;
        margin-top: 0.4em;
        z-index: 2001;
    }

    .image_moving {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        transform: translateY(-50%);
        display: flex;
        justify-content: space-between;
        width: 100%;
    }

    .previes_img_bottom, .next_img_bottom {
        cursor: pointer;
        font-size: 3.5em;
        color: #b3b2b2d9;
        padding: 0 12px;
    }

    .previes_img_bottom:hover, .next_img_bottom:hover {
        color: #ffffff;
    }
</style>
