<template>
    <div>
        <canvas 
            :id="canvas_id_prop" 
            class="canvas-style" 
            v-on:mousedown="mouseDown"
            v-on:mouseup="saveCanvasData"
        />
    </div>
</template>

<script>
    const paper = require('paper');
    export default {
        name: "Canvas",
        props: [
            'canvas_id_prop',
            'action_prop',
        ],
        data: () => ({
            path: null,
            scope: null,
            canvasData: null, // store canvas data
        }),
        mounted() {
            this.scope = new paper.PaperScope();
            this.scope.setup(this.canvas_id_prop);

            // paperjsLayersPanel.create();
        },
        methods: {
            reset() {
                this.scope.project.activeLayer.removeChildren();
                this.saveCanvasData()
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

            add_layer(){
                var layer = new paper.Layer({
                    children: [path, path2],
                    strokeColor: 'black',
                    position: view.center
                });
            },
            
            mouseDown() {
                this.tool = this.createTool(this.scope);

                this.tool.onMouseDown = (event) => {
                    if (this.action_prop == 1) {
                        this.add_line();
                    }
                    else if (this.action_prop == 2){
                        this.add_point(event);
                    }
                    else if (this.action_prop == 3){
                        this.add_line()
                        this.add_point(event)
                    }
                    else{}
                };

                this.tool.onMouseDrag = (event) => {
                    this.path.add(event);
                };
                
                this.tool.onMouseUp = (event) => {
                    this.path = []
                }
            },

            saveCanvasData() {
                const canvasData = JSON.stringify( this.scope.project.exportJSON());
                this.$emit('canvas_data', canvasData)
            }
        },
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