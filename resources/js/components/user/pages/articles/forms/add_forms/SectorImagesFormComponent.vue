<template>
    <form @submit="checkForm" ref="myForm">
        <div class="form-group clearfix row">
            <label for="email" class='col-md-6 control-label'>Upload sector view image:</label>
            <div class="col-md-6">
                <input type="file" name="image" id="image">
            </div>
        </div>
    </form>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort';
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        props: [
            'category'
        ],
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                myModal: false,
                // errors: [],
                image_errors: [],
                without_info: false,

                is_add_image: false,

                category: this.$route.params.article_category,

                image_is_refresh: false,
                image_reset_id: 0,
            }
        },
        mounted() {
        },
        methods: {
            add_image_modal(){
                this.is_add_image = true
            },

            // add_image(){
            //     var myFormData = new FormData(this.$refs.myForm)
            //     axios({
            //         method: 'post',
            //         url: '/gallery/add/',
            //         data: myFormData,
            //         config: { 
            //             headers: {'Content-Type': 'multipart/form-data' },
            //         },
            //     })
            //     .then((response)=>  {
            //         this.is_add_image = false
            //         // this.get_gallery_data()
            //     });
            // },

            add_spot_rock_image: function () {
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
                    
                });
            },
        }
    }
</script>

<style>

</style>