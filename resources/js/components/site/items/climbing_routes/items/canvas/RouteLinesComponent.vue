<template>
    <div >
        <canvas 
            id="canvas_id_prop" 
            class="canvas-style"
        />
    </div>
</template>

<script>
    // http://paperjs.org/
    const paper = require('paper');
    export default {
        // name: "Canvas",
        props: [
            // 'json_prop'
        ],
        data: () => ({
            // path: null,
        }),
        watch: {
            // json_prop: function(){
            //     this.old_json = this.json_prop
            // },
        },
        mounted() {
            var path = new paper.Path.Circle(new paper.Point(80, 50), 30);
            path.style = {
                fillColor: new Color(1, 0, 0),
                strokeColor: 'black',
                strokeWidth: 5
            };

            path.paper.setup(document.getElementById('canvas_id_prop'))
        },
        methods: {
            // 
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