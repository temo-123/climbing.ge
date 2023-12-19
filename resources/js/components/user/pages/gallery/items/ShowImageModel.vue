
<template>
    <stack-modal
        :show="is_show_image"
        title="Image"
        @close="is_show_image=false"
        :saveButton="{ visible: false, title: 'Save', btnClass: { 'btn btn-primary': false } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <div class="container">
                <div class="row">
                    <img :src="'/images/slider_img/' + modal_image.image" :alt="modal_image.title">
                </div>
            </div>
        </pre>
    </stack-modal>
</template>

<script>
    // import breadcrumb from '../../items/BreadcrumbComponent.vue'
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            // SlickItem,
            // SlickList,
        },
        data(){
            return{
                user: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            }
        },
        mounted(){
            // 
        },
        methods: {
            // get_user_data(){
            //     axios
            //     .get('/api/auth_user')
            //     .then((response)=>{
            //         this.user = response.data
            //         this.get_user_queries(this.user.id)
            //     })
            // },
        }
    }
</script>
