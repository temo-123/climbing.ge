<template>
    <form>
        <div class="form-group clearfix row">
            <label for="email" class='col-md-6 control-label'>Upload post image:</label>
            <div class="col-md-6">
                <input type="file" name="image" id="image" required v-on:change="onFileChange">
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        components: {
            //
        },
        props: [
            // 
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
                this.image = e.target.files[0];

                this.upload_img()
            },
            upload_img(event){
                this.$emit("upload_img", this.image);
            },
        }
    }
</script>

<style>

</style>