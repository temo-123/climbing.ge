<template>
  <div class="verify">
    <div class="alert alert-danger" role="alert" v-if="error">
      {{ error }}
    </div>
    <h1 v-show="!error">Please wait..</h1>
  </div>
</template>

<script>
export default {
    data() {
      return {
        error: null
      };
    },
    mounted() {     
        document.querySelector('body').style.marginLeft = '0';
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';

        this.varificate()
    },
    methods: {
        varificate(){
            axios
            .get('email/verify/' + this.$route.params.user_id + '/' + this.$route.params.hash)
            .then((response)=>{
                alert(response.data + ' You will return on login page automaticly!');
                this.goTo('/login')
                window.close();
            })
            .catch((error) => {
                // if(error.response.status = 400){
                //   alert(error.response.data + ' Page is will close automatically!')
                // }
                // else{
                  alert('Something went wrong! Please try again later, if you encounter this problem again, contact support! Page is will close automatically!')
                // }
                window.close();
            })
            .finally(() => this.is_email_sending_loader = false);
        },
        goTo(page = '/'){
            window.open(process.env.MIX_APP_SSH + 'user.' + process.env.MIX_SITE_URL + page) ;
        },
    }
};
</script>