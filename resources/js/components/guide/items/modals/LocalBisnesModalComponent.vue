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
                            v-for="img in bisnes_data.images"
                            :key="img.id"
                            class="bisnes-gallery-item"
                        >
                            <img
                                :src="'/public/images/suport_local_bisnes_img/' + img.image"
                                :alt="bisnes_data.locale_data.title"
                            />
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
                this.show = false
            }
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
</style>
