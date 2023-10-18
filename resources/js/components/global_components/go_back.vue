<script>
    // import GlobalDataForm from './forms/add_forms/GlobalDataFormComponent.vue'

    export default {
        components: {
            // GlobalDataForm,
        },
        props: [
            // 'back_url',
        ],
        data(){
            return {
                // tab_num: 1
            }
        },
        mounted() {
            //
        },
        methods: {
            go_back: function(back_action = false) {
                if(back_action == false){
                    if(confirm('Are you sure, you want go back?')){
                        this.$router.go(-1)
                    }
                }
                else{
                    this.$router.go(-1)
                }
            },
        }
    }
</script>