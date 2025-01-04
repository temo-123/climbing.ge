<template>
    <div>
        <div class="row ">
            
            <div class="col-md-2">
                <button class="btn btn-danger btn-block" @click.prevent="reset">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>

                <button class="btn btn-warning btn-block" @click.prevent="back">
                    <i class="fa fa-step-backward" aria-hidden="true"></i>
                </button>

                <hr />

                <button class="btn btn-primary btn-block but_action" @click.prevent="line" v-if="action == 1">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>
                <button class="btn btn-primary btn-block" @click.prevent="line" v-else>
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>

                <button class="btn btn-primary btn-block but_action" @click.prevent="point" v-if="action == 2">
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </button>
                <button class="btn btn-primary btn-block" @click.prevent="point" v-else>
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </button>

                <button class="btn btn-primary btn-block but_action" @click.prevent="number" v-if="action == 3">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                     +
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </button>
                <button class="btn btn-primary btn-block" @click.prevent="number" v-else>
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                     +
                    <i class="fa fa-circle" aria-hidden="true"></i>
                </button>

                <button class="btn btn-primary btn-block but_action" @click.prevent="rectangle" v-if="action == 4">
                    <i class="fa fa-square-o" aria-hidden="true"></i>
                </button>
                <button class="btn btn-primary btn-block" @click.prevent="rectangle" v-else>
                    <i class="fa fa-square-o" aria-hidden="true"></i>
                </button>
            </div>

            <div class="col-md-10">
                <Canvas 
                    :canvas_id_prop="'canvas-one'" 
                    :action_prop="action"
                    :json_prop="json_prop"

                    ref="childCanvas"

                    :style="{ backgroundImage: 'url(' + image + ')' }"
                    @canvas_data="canvas_data"
                />
            </div>

        </div>
    </div>
</template>

<script>
    import Canvas from "./PaperComponent.vue";
    export default {
        components: {
            Canvas
        },
        props: [
            'json_prop'
        ],
        data: () => ({
            image: '',

            img_h: 0,
            img_w: 0,

            action: 1,
        }),
        mounted() {
            // var img = new Image();
            // img.src = this.image
            // img.onload = function() {
            //     this.img_h = this.height/2
            //     this.img_w = this.width/2
            //     // alert(this.width + 'x' + this.height);
            //     alert(this.img_w + 'x' + this.img_h);
            // }
        },
        methods: {
            reset() {
                if (confirm('Do you want clear area?')) {
                    this.$refs.childCanvas.reset();
                }
            },

            point(){
                this.action = 2
            },

            line(){
                this.action = 1
            },

            number(){
                this.action = 3
            },

            rectangle(){
                this.action = 4
            },

            change_image(event){
                this.image = '/public/images/sector_img/' + event
            },

            canvas_data(event){
                this.$emit('canvas_data', event)
            },

            import_json_in_editor(event){
                console.log("🚀 ~ import_json_in_editor ~ event:", event)
                // this.$refs.childCanvas.import_json(json)
            }
        },
    }
</script>

<style scoped>
    .but_action{
        background-color: #a5a6a7;
    }
</style>