<template>
  <div class="verify">
    <div class="alert alert-danger" role="alert" v-if="error">
      {{ error }}
    </div>
    <h1 v-show="!error">Please wait..</h1>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  props: ["hash"],
  data() {
    return {
      error: null
    };
  },
  mounted() {
    this.sendVerifyRequest(this.hash)
      .then(() => {
        this.$router.push("/");
      })
      .catch(error => {
        console.log(error.response);
        this.error = "Error verifying email";
      });
      
      document.querySelector('body').style.marginLeft = '0';
      document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },
  computed: {
    ...mapGetters("auth", ["user"])
  },
  methods: {
    ...mapActions("auth", ["sendVerifyRequest"])
  }
};
</script>