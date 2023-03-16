<template>
    <div class="conteiner">
        <h1>Please Wait...</h1>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                token: this.$route.query.token ? this.$routr.query.token : null
            }
        },
        mounted(){
            this.$auth.login({ data : {
                token: this.token
            } })
            .catch( (e)=> {
                return this.$routr.push('/auth/register?error=Your token appered to be invalid, please try again.'); 
            })
        }
    }
</script>

<style>

</style>