<template>
    <span>
        <button type="submit" class="btn btn-default btn-send main-btn" @click="show_modal">Check routes authers</button>

        <stack-modal
            :show="is_show_modal"
            title="Routes authers list"
            @close="is_show_modal=false"
            :saveButton="{ visible: false, title: 'Save', btnClass: { 'btn btn-primary': false } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <pre class="language-vue">
                tab
            </pre>
        </stack-modal>
    </span>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
        },
        data(){
            return{
                authers: [],
                is_show_modal: false,
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            }
        },
        mounted(){
            // 
        },
        methods: {
            show_modal(){
                axios
                .get('/route/routes_authers/')
                .then(response => {
                    this.authers = response.data
                    this.is_show_modal = true
                })
                .catch(error =>{
                })
            }
        }
    }
</script>
