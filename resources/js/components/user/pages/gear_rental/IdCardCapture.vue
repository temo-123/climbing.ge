<template>
    <!-- ID card photo: upload a file or take a photo with the camera (webcam on a
         computer, back camera on a phone). The picture is shrunk to max 1800 px JPEG
         in the browser, then handed to the form with `update:file`. -->
    <div class="idc">
        <div v-if="preview" class="idc__preview">
            <img :src="preview" :alt="$t('admin.gear_rental.id_card')">
            <button type="button" class="btn btn-sm btn-light idc__clear" @click="clear" :aria-label="$t('common.delete')">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>

        <div v-else-if="camera_on" class="idc__camera">
            <video ref="video" autoplay playsinline muted></video>
            <div class="idc__camera-actions">
                <button type="button" class="btn btn-primary" @click="snap">
                    <i class="fa fa-camera" aria-hidden="true"></i> {{ $t('admin.gear_rental.id_card_snap') }}
                </button>
                <button type="button" class="btn btn-light" @click="stop_camera">{{ $t('common.cancel') }}</button>
            </div>
        </div>

        <div v-else class="idc__actions">
            <label class="btn btn-outline-primary mb-0">
                <i class="fa fa-upload" aria-hidden="true"></i> {{ $t('admin.gear_rental.id_card_upload') }}
                <input type="file" accept="image/*" class="d-none" @change="on_file">
            </label>
            <button v-if="can_camera" type="button" class="btn btn-outline-primary" @click="start_camera">
                <i class="fa fa-camera" aria-hidden="true"></i> {{ $t('admin.gear_rental.id_card_take') }}
            </button>
            <!-- no camera API (old browser / http): the phone's camera app through the file input -->
            <label v-else class="btn btn-outline-primary mb-0">
                <i class="fa fa-camera" aria-hidden="true"></i> {{ $t('admin.gear_rental.id_card_take') }}
                <input type="file" accept="image/*" capture="environment" class="d-none" @change="on_file">
            </label>
        </div>
        <div v-if="error" class="text-danger small mt-1">{{ error }}</div>
    </div>
</template>

<script>
const MAX_SIDE = 1800

export default {
    name: 'IdCardCapture',
    emits: ['update:file'],
    data() {
        return { preview: null, camera_on: false, error: '', stream: null }
    },
    computed: {
        can_camera() {
            return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) && window.isSecureContext !== false
        },
    },
    beforeUnmount() {
        this.stop_camera()
        if (this.preview) URL.revokeObjectURL(this.preview)
    },
    methods: {
        reset() {
            this.stop_camera()
            if (this.preview) URL.revokeObjectURL(this.preview)
            this.preview = null
            this.error = ''
        },
        clear() {
            this.reset()
            this.$emit('update:file', null)
        },
        on_file(e) {
            const file = e.target.files && e.target.files[0]
            e.target.value = ''
            if (!file) return
            if (!/^image\//.test(file.type)) { this.error = this.$t('admin.gear_rental.id_card_not_image'); return }
            this.error = ''
            const url = URL.createObjectURL(file)
            const img = new Image()
            img.onload = () => { this.from_source(img, img.naturalWidth, img.naturalHeight); URL.revokeObjectURL(url) }
            img.onerror = () => { URL.revokeObjectURL(url); this.set_file(file) }   // unreadable here (e.g. HEIC) → send as is
            img.src = url
        },
        async start_camera() {
            this.error = ''
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' }, width: { ideal: 1920 }, height: { ideal: 1080 } }, audio: false })
                this.camera_on = true
                this.$nextTick(() => { if (this.$refs.video) this.$refs.video.srcObject = this.stream })
            } catch (e) {
                this.error = this.$t('admin.gear_rental.id_card_no_camera')
                this.stop_camera()
            }
        },
        stop_camera() {
            if (this.stream) this.stream.getTracks().forEach(t => t.stop())
            this.stream = null
            this.camera_on = false
        },
        snap() {
            const v = this.$refs.video
            if (!v || !v.videoWidth) return
            this.from_source(v, v.videoWidth, v.videoHeight)
            this.stop_camera()
        },
        // draw → shrink → JPEG
        from_source(source, w, h) {
            const scale = Math.min(1, MAX_SIDE / Math.max(w, h))
            const canvas = document.createElement('canvas')
            canvas.width = Math.round(w * scale)
            canvas.height = Math.round(h * scale)
            canvas.getContext('2d').drawImage(source, 0, 0, canvas.width, canvas.height)
            canvas.toBlob(blob => {
                if (!blob) { this.error = this.$t('admin.gear_rental.id_card_not_image'); return }
                this.set_file(new File([blob], 'id_card.jpg', { type: 'image/jpeg' }))
            }, 'image/jpeg', 0.88)
        },
        set_file(file) {
            if (this.preview) URL.revokeObjectURL(this.preview)
            this.preview = URL.createObjectURL(file)
            this.$emit('update:file', file)
        },
    },
}
</script>

<style scoped>
.idc__actions { display: flex; flex-wrap: wrap; gap: .5rem; }
.idc__preview { position: relative; display: inline-block; max-width: 100%; }
.idc__preview img {
    display: block;
    max-width: 100%;
    max-height: 260px;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r);
}
.idc__clear { position: absolute; top: 6px; right: 6px; }
.idc__camera video {
    display: block;
    width: 100%;
    max-height: 360px;
    background: #000;
    border-radius: var(--cg-r);
}
.idc__camera-actions { display: flex; gap: .5rem; margin-top: .5rem; }
</style>
