<template>
    <form @submit="checkForm" ref="myForm">
        <div class="form-group clearfix row">
            <label for="email" class='col-md-5 control-label'>Upload article image:</label>
            <div class="col-md-6">
                <input type="file" name="image" id="image">
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
                // sucses_upload: true
            }
        },
        mounted() {
            // 
        },
        methods: {
            checkForm: function () {
                console.log('test')
                var myFormData = new FormData(this.$refs.myForm)
                axios({
                    method: 'post',
                    url: '../../api/articles/upload_image/',
                    data: myFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    this.upload_sucses()
                });
            },
            upload_sucses(){
                // this.sucses_upload = true
                this.$emit("sucses_upload", false);
            },
        }
    }
</script>

<style>

</style>