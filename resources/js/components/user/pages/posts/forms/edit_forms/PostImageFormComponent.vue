<template>
    <div class="col-md-12">
        <form ref="myForm" id="myForm" enctype="multipart/form-data">
            <div class="form-group clearfix row">
                <label for="post image" class='col-md-2 control-label'>Active image:</label>
                <div class="col-md-5">
                    <img :src="'/public/images/blog_img/' + image_prop" alt="Post image">
                </div>
            </div>

            <div class="form-group clearfix row">
                <label for="new image" class='col-md-2 control-label'>Upload new image:</label>
                <div class="col-md-10">
                    <input type="file" name="image" id="image" v-on:change="onFileChange">
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        components: {
            //
        },
        props: [
            'image_prop',
        ],
        data(){
            return {
                // image: ''
            }
        },
        mounted() {
            // 
        },
        methods: {
            onFileChange(e){
                this.$emit("upload_img", e.target.files[0])
            },
        }
    }
</script>

<style>

</style>