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
    // import "https://sasensi.github.io/paperjs-layers-panel/build/paperjs-layers-panel.js"
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

                    // new paper.Path.Circle({
                    //     center   : event.point,
                    //     radius   : 50,
                    //     fillColor: paper.Color.random()
                    // });

                    if (self.action == 1) {
                        self.add_line();
                    }
                    else if (self.action == 2){
                        self.add_point(event);
                    }
                    else if (self.action == 3){
                        this.add_line()
                        this.add_point(event)
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

            // set active layer name
            paper.project.activeLayer.name = 'text layer';

            var text = new paper.PointText();
            text.point = (250, 250)
            text.justification = 'center';
            text.fillColor = 'black';
            text.content = 'The contents of the point text';

            // var layer = new paper.Layer({name: 'circles layer'});
            // layer.sendToBack();
            // layer.activate();

            var firstLayer = paper.project.activeLayer;
            var secondLayer = new paper.Layer();
            console.log(paper.project.activeLayer == secondLayer); // true
            firstLayer.activate();
            console.log(paper.project.activeLayer == firstLayer); // true

            paperjsLayersPanel.create();

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


    /* stylize panel */
    paperjs-layer-panel main {
        position : fixed;
        top      : 15px;
        z-index  : 1;
        left     : 15px;
    }
</style>