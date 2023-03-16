<template>
    <div>
        <canvas 
            :id="canvasId" 
            class="canvas-style" 
            v-on:mousedown="mouseDown"
        />
    </div>
</template>

<script>
    // import { Raster } from 'paper/dist/paper-core';
    // TODO: move all of this logic to master
    // https://noahkreiger.medium.com/vuejs-paperjs-a-canvas-story-c412dc161b1f
    // packages
    const paper = require('paper');
    export default {
        name: "Canvas",
        props: [
            'canvasId',
            'action',
            'image',
            'img_pos_h',
            'img_pos_w'
        ],
        data: () => ({
            path: null,
            scope: null,
        }),
        methods: {
            reset() {
                this.scope.project.activeLayer.removeChildren();
            },
            createTool(scope) {
                scope.activate();
                return new paper.Tool();
            },
            add_point_and_line(event){
                this.add_line()
                this.add_point(event)
            },
            add_point(event){
                return new paper.Path.Circle({
                    center: event.point,
                    radius: 10,
                    fillColor: 'white',
                    strokeColor: '#ff0000'
                });
            },
            add_line(){
                this.path = new paper.Path({
                    strokeColor: "#ff0000",
                    strokeWidth: 3,
                    strokeJoin: 'round',
                });
            },
            mouseDown() {
                // in order to access functions in nested tool
                let self = this;
                // create drawing tool
                this.tool = this.createTool(this.scope);

                this.tool.onMouseDown = (event) => {

                    if (self.action == 1) {
                        self.add_line();
                    }
                    else if (self.action == 2){
                        self.add_point(event);
                    }
                    else if (self.action == 3){
                        self.add_point_and_line(event);
                    }
                    else{}
                };

                this.tool.onMouseDrag = (event) => {
                    self.path.add(event);
                };
                
                this.tool.onMouseUp = (event) => {
                    // line completed
                    // self.path.add(event.point);
                    self.path = []
                }
            }
        },
        mounted() {
            this.scope = new paper.PaperScope();
            this.scope.setup(this.canvasId);

            let img = new paper.Raster(this.image)
            img.position = new paper.Point(this.img_pos_h, this.img_pos_w);



            var path = new paper.Path([100, 100], [100, 200]);
            var path2 = new paper.Path([50, 150], [150, 150]);
            // var layer = [];
            // Create a layer. The properties in the object literal
            // are set on the newly created layer.
            var layer = new paper.Layer({
                children: [path, path2],
                strokeColor: 'black',
                position: view.center
            });

        }
    }
</script>

<style scoped>
    .canvas-style {
        cursor: crosshair;
        width: 100% !important;
        height: 500px !important;
        border: 2px solid rgb(139, 139, 139);
        /* border-radius: 10px; */
        display: block;
        margin: auto;
        /* box-shadow: 0 10px 8px -8px black; */
    }
</style>