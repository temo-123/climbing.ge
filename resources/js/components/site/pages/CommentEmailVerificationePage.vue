<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1> Confirm your email </h1>
                    <div class="error-actions">
                        <p>{{$route.params.email}}</p>
                        <button @click="confirm_email()" class="btn btn-primary btn-lg">
                            Confirm
                        </button>
                    </div>
                    <h2>Climbing.ge</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            // 'article',
        ],
        data: function () {
            return {
                // 
            };
        },
        components: {
            // 
        },
        mounted() {
            // 
        },
        watch: {
            '$route' (to, from) {
                window.scrollTo(0,0)
            }
        },
        methods: {
            confirm_email(){
                axios
                .post('/guide_comment/confirm_email/'+this.$route.params.email)
                .then(response => {
                    alert('Your email is confirmed! This page will be closed!')
                    window.close();
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>

<style scoped>

</style>