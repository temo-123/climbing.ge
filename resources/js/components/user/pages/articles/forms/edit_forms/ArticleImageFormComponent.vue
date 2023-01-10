<template>
    <div class="col-md-12">
        <form ref="myForm" id="myForm" enctype="multipart/form-data">
            <div class="form-group clearfix row">
                <label for="article image" class='col-md-2 control-label'>Active image:</label>
                <div class="col-md-5">
                    <img :src="'/public/images/'+category_prop+'_img/'+image_prop" alt="Locale sectors image">
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
            'category_prop',
        ],
        data(){
            return {
                image: ''
            }
        },
        mounted() {
            // 
        },
        methods: {
            onFileChange(e){
                // this.image = e.target.files[0];

                this.upload_img(e.target.files[0])
            },
            upload_img(event){
                this.$emit("upload_img", event);
            },
        }
    }
</script>

<style>

</style>