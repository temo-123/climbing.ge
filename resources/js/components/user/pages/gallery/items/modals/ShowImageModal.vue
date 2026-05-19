
<template>
    <stack-modal
        :show="is_show_image"
        :title="$t('head_slider.preview')"
        @close="close_modal()"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
        size="xl"
    >
        <div>
            <span v-show="is_loading">
                <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
            </span>
            <div class="preview_wrap" v-show="!is_loading">
                <img :src="image_path_prop + modal_image.image" :alt="modal_image.title" class="preview_img">

                <div class="preview_overlay"></div>

                <div
                    v-if="modal_image.title || modal_image.text"
                    class="preview_content"
                    :class="'pos-' + (modal_image.text_position || 'center')"
                >
                    <div class="preview_title" v-if="modal_image.title">{{ modal_image.title }}</div>
                    <div class="preview_desc" v-if="modal_image.text">{{ modal_image.text }}</div>
                    <a v-if="modal_image.link" :href="modal_image.link" class="preview_btn">{{ $t('head_slider.read_more') }}</a>
                </div>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    export default {
        components: {},
        data(){
            return{
                modal_image: [],
                is_show_image: false,
                is_loading: false,
            }
        },
        props: ['image_path_prop'],
        methods: {
            show_modal(image_id){
                this.is_show_image = true
                this.get_actyve_image(image_id)
            },
            close_modal(){
                this.is_show_image = false
                this.modal_image = []
            },
            get_actyve_image(id){
                this.is_loading = true
                axios
                .get("get_head_slider/get_actyve_slide/"+id)
                .then(response => {
                    this.modal_image = response.data
                })
                .catch(error => console.log(error))
                .finally(() => this.is_loading = false);
            }
        }
    }
</script>

<style scoped>
.preview_wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    background: #111;
    overflow: hidden;
    border-radius: 6px;
}

.preview_img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.preview_overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(0,0,0,0.05) 0%,
        rgba(0,0,0,0.15) 40%,
        rgba(0,0,0,0.65) 100%
    );
}

.preview_content {
    position: absolute;
    padding: 14px 18px;
    background: rgba(0, 0, 0, 0.48);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    border-radius: 8px;
    max-width: 58%;
}

.pos-center      { bottom: 12%; left: 50%; transform: translateX(-50%); text-align: center; }
.pos-left-top    { top: 8%; left: 5%; text-align: left; }
.pos-right-top   { top: 8%; right: 5%; text-align: right; }
.pos-left-bottom { bottom: 8%; left: 5%; text-align: left; }
.pos-right-bottom{ bottom: 8%; right: 5%; text-align: right; }

.preview_title {
    color: #fff;
    font-size: clamp(1rem, 2.5vw, 1.8rem);
    font-weight: 700;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    margin-bottom: 0.4rem;
    line-height: 1.2;
}

.preview_desc {
    color: rgba(255,255,255,0.9);
    font-size: clamp(0.8rem, 1.5vw, 1.1rem);
    text-shadow: 0 1px 6px rgba(0,0,0,0.5);
    margin-bottom: 0.8rem;
    line-height: 1.4;
}

.preview_btn {
    display: inline-block;
    padding: 6px 18px;
    border: 2px solid #fff;
    color: #fff;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    text-transform: uppercase;
    border-radius: 2px;
    background: rgba(255,255,255,0.1);
    transition: background 0.2s, color 0.2s;
}

.preview_btn:hover {
    background: #fff;
    color: #222;
    text-decoration: none;
}
</style>
