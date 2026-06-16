<template>
    <span>
        <div class="smal_img" @click="open_image()">
            <img :src="img" :alt="img_alt" :class="img_class + ' cursor_pointer'" />
        </div>

        <Teleport to="body">
            <div class="open_img" v-if="open_img" @click="close_image()">
                <div class="close_bottom cursor_zoom_out" @click.stop="close_image()">X</div>
                <img
                    :src="img"
                    :alt="img_alt"
                    class="big_img_position cursor_zoom_out zoom"
                    style="max-width:96%;max-height:80%;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);"
                />
            </div>
        </Teleport>
    </span>
</template>

<script>
    export default {
        props: ['img', 'img_alt', 'img_class'],
        data() {
            return {
                open_img: false,
            };
        },
        mounted() {
            this._closeHandler = () => {
                if (this.open_img) {
                    this.open_img = false;
                    document.body.classList.remove('body_hiden');
                }
            };
            window.addEventListener('imageclose', this._closeHandler);
        },
        beforeUnmount() {
            window.removeEventListener('imageclose', this._closeHandler);
            this._removePopstateHandler();
            if (this.open_img) {
                window.__imageOpen = false;
                document.body.classList.remove('body_hiden');
            }
        },
        methods: {
            open_image() {
                this.open_img = true;
                window.__imageOpen = true;
                document.body.classList.add('body_hiden');
                this._savedUrl = location.href;
                history.pushState({ imageOpen: true }, '', location.href);
                this._popstateHandler = () => {
                    // Back button pressed while image is open — close image and stay on same URL
                    history.pushState(null, '', this._savedUrl);
                    this.close_image();
                };
                window.addEventListener('popstate', this._popstateHandler);
            },
            close_image() {
                if (!this.open_img) return;
                this.open_img = false;
                window.__imageOpen = false;
                document.body.classList.remove('body_hiden');
                this._removePopstateHandler();
            },
            _removePopstateHandler() {
                if (this._popstateHandler) {
                    window.removeEventListener('popstate', this._popstateHandler);
                    this._popstateHandler = null;
                }
            },
        }
    }
</script>

<style scoped>

    .close_bottom{
        float: right;
        cursor: pointer; 
        color: #b3b2b2d9;
        font-size: 2em;
        margin-right: 0.4em;
        margin-top: 0.4em;
    }

    @media (max-width: 756px) {
        .close_bottom{
            position: absolute;
            left: 50%;
            right: 0;
            cursor: pointer;
            color: #b3b2b2d9;
            font-size: 2em;
            margin-right: 0.4em;
            margin-top: 0.4em;
        }
    }
    .smal_img img{
        height: 100%;
        cursor: zoom-in;
    }
    .open_img{
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1050;
        background: #000000d9;

        transition: opacity .15s linear;
    }
    .big_img_position{
        max-width: 96%;
        max-height: 80%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>