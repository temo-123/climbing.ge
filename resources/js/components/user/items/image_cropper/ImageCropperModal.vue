<template>
    <StackModal
        :show="show"
        :title="$t('admin.image_cropper.crop_image_title', { width: ratio_prop.width, height: ratio_prop.height })"
        size="lg"
        :saveButton="{ visible: true, title: $t('admin.image_cropper.crop_use_btn') }"
        :cancelButton="{ visible: true, title: $t('admin.comments.cancel_btn') }"
        @close="cancel"
        @save="confirm"
    >
        <div class="image-cropper-stage">
            <cropper
                v-if="imgSrc"
                ref="cropperRef"
                class="image-cropper-cropper"
                :src="imgSrc"
                :stencil-props="stencilProps"
                :canvas="canvasSizeLimit"
                image-restriction="stencil"
            />
        </div>
    </StackModal>
</template>


<script>
    import { Cropper } from 'vue-advanced-cropper'
    import 'vue-advanced-cropper/dist/style.css'

    export default {
        name: 'ImageCropperModal',
        components: { Cropper },
        props: {
            show: { type: Boolean, default: false },
            file: { type: File, default: null },
            ratio_prop: { type: Object, default: () => ({ width: 16, height: 9 }) },
        },
        emits: ['confirm', 'cancel'],
        data() {
            return {
                imgSrc: null,
            }
        },
        computed: {
            stencilProps() {
                return { aspectRatio: this.ratio_prop.width / this.ratio_prop.height }
            },
            canvasSizeLimit() {
                // Hero/head-slider source photos can be very high resolution (e.g. a
                // 6000x4000 professional shot). Without this cap, getResult() renders
                // the crop at that native resolution, producing a multi-megabyte
                // canvas that can choke the compression step downstream. Capping the
                // *result* canvas here (independent of the on-screen stencil size)
                // keeps every crop's output within a sane, fast-to-compress range.
                return { maxWidth: 1920, maxHeight: 1920 }
            },
        },
        watch: {
            show(val) {
                if (val && this.file) {
                    this.loadFile(this.file)
                } else {
                    this.reset()
                }
            },
        },
        beforeUnmount() {
            if (this.imgSrc) URL.revokeObjectURL(this.imgSrc)
        },
        methods: {
            loadFile(file) {
                if (this.imgSrc) URL.revokeObjectURL(this.imgSrc)
                this.imgSrc = URL.createObjectURL(file)
            },

            confirm() {
                const result = this.$refs.cropperRef?.getResult()
                if (!result || !result.canvas) {
                    this.cancel()
                    return
                }
                result.canvas.toBlob((blob) => {
                    if (!blob) {
                        this.cancel()
                        return
                    }
                    const name = (this.file?.name || 'image').replace(/\.[^.]+$/, '.jpg')
                    const cropped = new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() })
                    this.$emit('confirm', cropped)
                    this.reset()
                }, 'image/jpeg', 0.92)
            },

            cancel() {
                this.$emit('cancel')
                this.reset()
            },

            reset() {
                if (this.imgSrc) URL.revokeObjectURL(this.imgSrc)
                this.imgSrc = null
            },
        }
    }
</script>


<style scoped>
.image-cropper-stage {
    width: 100%;
    height: 55vh;
    min-height: 320px;
}
.image-cropper-cropper {
    width: 100%;
    height: 100%;
}
</style>
