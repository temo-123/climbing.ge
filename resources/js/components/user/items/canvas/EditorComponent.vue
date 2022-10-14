<template>
    <div>
        <div class="row ">
            
            <div class="col-md-2">
                <button class="btn btn-danger btn-block" @click.prevent="reset">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
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
                     <i class="fa fa-circle" aria-hidden="true"></i>
                     +
                     <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>
                <button class="btn btn-primary btn-block" @click.prevent="number" v-else>
                     <i class="fa fa-circle" aria-hidden="true"></i>
                     +
                     <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>
                <button class="btn btn-primary btn-block" @click.prevent="upload">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                </button>
                <button class="btn btn-primary btn-block" @click.prevent="back">
                    <i class="fa fa-undo" aria-hidden="true"></i>
                </button>
                <button class="btn btn-primary btn-block" @click.prevent="save">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </button>
            </div>

            <div class="col-md-10">
                <Canvas 
                    :canvas-id="'canvas-one'" 
                    :action="action"

                    ref="childCanvas"
                />
                    <!-- style="background-image: url(http://user.climbing.loc/images/gallery_img/2021-11-27-19-11-56.jpg);"  -->
            </div>

        </div>
    </div>
</template>

<script>
    import Canvas from "./PaperComponent.vue";
    export default {
        name: "Master",
        data: () => ({
            buttons: ['Simple', 'Separate', 'Replicated'],
            single: true,
            // image: 'http://user.climbing.loc/images/gallery_img/2021-11-27-19-11-56.jpg',
            img_h: 0,
            img_w: 0,
            action: 1,
        }),
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
            upload(){
                
            },
            save() {
                
            }
        },
        components: {
            Canvas
        },
        mounted() {
            var img = new Image();
            img.src = this.image
            img.onload = function() {
                this.img_h = this.height/2
                this.img_w = this.width/2
                // alert(this.width + 'x' + this.height);
                alert(this.img_w + 'x' + this.img_h);
            }
        }
    }
</script>

<style scoped>
    .but_action{
        background-color: #a5a6a7;
    }
</style>