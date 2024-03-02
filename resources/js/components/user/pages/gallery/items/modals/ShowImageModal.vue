
<template>
    <stack-modal
        :show="is_show_image"
        title="Image"
        @close="close_modal()"
        :saveButton="{ visible: false, title: 'Save', btnClass: { 'btn btn-primary': false } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <div class="container">
                <span v-show="is_loading">
                    <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
                </span>
                <div class="row" v-show="!is_loading">
                    <img :src="image_path_prop + modal_image.image" :alt="modal_image.title">
                </div>
            </div>
        </pre>
    </stack-modal>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
        },
        data(){
            return{
                modal_image: [],
                is_show_image: false,
                is_loading: false,
            }
        },
        mounted(){
            // 
        },
        props: [
            'image_path_prop',
        ],
        methods: {
            show_modal(image_id){
                this.is_show_image = true
                
                this.get_actyve_image(image_id)
            },
            close_modal(){
                this.is_show_image = false
                this.modal_image = []
            },
            get_actyve_image(id){
                this.is_loading = true
                axios
                .get("/head_slider/get_actyve_slide/"+id)
                .then(response => {
                    this.modal_image = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() =>
                    this.is_loading = false
                );
            }
        }
    }
</script>
