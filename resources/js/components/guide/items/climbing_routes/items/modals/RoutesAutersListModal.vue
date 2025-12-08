<template>
    <span>
        {{ $t('guide.sector.authers_list') }} <a @click="show_modal"> {{ $t('guide.sector.authers_list_button') }}</a>
        <!-- <button type="submit" class="btn btn-default btn-send main-btn" @click="show_modal">{{ $t('guide.sector.authers_list_button') }}</button> -->

        <stack-modal
            :show="is_show_modal"
            title="Routes authers list"
            @close="is_show_modal=false"
            :saveButton="{ visible: false, title: 'Save', btnClass: { 'btn btn-primary': false } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <pre class="language-vue">
                <table class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th>{{ $t('guide.sector.auther_name') }}</th>
                            <th>|</th>
                            <th>{{ $t('guide.sector.auther_routes_quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(author, index) in authers" :key="index">
                            <td>{{index}}</td>
                            <td>|</td>
                            <td>{{author}}</td>
                        </tr>
                    </tbody>
                </table>
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
                // MIX_SITE_URL: process.env.MIX_SITE_URL,
                // MIX_APP_SSH: process.env.MIX_APP_SSH,
            }
        },
        mounted(){
            // 
        },
        methods: {
            show_modal(){
                axios
                .get('/get_route/routes_authers/')
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

<style>
.language-vue{
    margin: 10px 0 0 0;
    width: 95%;

    position: absolute;
    top: 0;
    bottom: 0;
    overflow-y: scroll;
    overflow-x: hidden;
}
.modal-body{
    height: 600px;
}
th:nth-child(2n+1) {
  background: #d7b396;
}
</style>