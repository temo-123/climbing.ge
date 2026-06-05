<template>
  <StackModal
    v-model="is_show_modal"
    title="Please select a reason for deleting the feedback"
    size="lg"
    :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
    :cancelButton="{ visible: true, title: 'Close', btnClass: { 'btn btn-secondary': true } }"
    @close="close_modal"
  >
    <div class="row justify-content-center" v-if="complaint_loader">
      <div class="col-md-4">
        <img :src="'../public/images/site_img/loading.gif'" alt="loading">
      </div>
    </div>

    <div v-else>
      <h1>You can file a complaint for this feedback</h1>
      <p>Please select a reason for deleting the feedback!!!</p>
      
      <form @submit.prevent="make_complaint" id="make_complaint" class="form">
        <input v-if="user.length == 0" type="email" name="complainter email" v-model="complainter_email" class="form-control textarea" placeholder="Your email">

        <select class="form-control" v-model="selected_feedback_complaint" name="feedback delete cause"> 
          <option value="Hostile remarks">Hostile remarks</option>
          <option value="Does not match the theme of the site">Does not match the theme of the site</option>
          <option value="Spam">Spam</option>
          <option value="Sexual content">Sexual content</option>
          <option value="Expression of anger">Expression of anger</option>
          <option value="Conflict with other members of the site">Conflict with other members of the site</option>
          <option value="The language of the feedbacks does not match the requirements of the site">The language of the feedbacks does not match the requirements of the site</option>
        </select>
      </form>

      <!-- Submit button in content -->
      <div class="text-center mt-4 pt-3 border-t">
        <button
          type="submit"
          class="btn btn-primary"
          form="make_complaint"
          :disabled="!is_verify_isset"
        >
          Submit
        </button>
      </div>
    </div>
  </StackModal>
</template>

<script>
export default {
  props: ["product_id"],
  data() {
    return {
      ModalClass: "",
      is_verify_isset: false,
      complaint_feedback_id: 0,
      selected_feedback_complaint: 'Hostile remarks',
      is_show_modal: false,
      complaint_loader: false,
      complainter_email: '',
      user: [],
      errors: []
    }
  },
  mounted() {
    this.get_user_info()
  },
  methods: {
    show_modal(feedback_id) {
      this.complaint_feedback_id = feedback_id
      this.is_show_modal = true
      this.complaint_loader = false
    },
    close_modal() {
      this.is_show_modal = false
    },
    get_user_info() {
      axios
        .get('/auth_user/')
        .then(response => {
          this.user = response.data
          this.complainter_email = this.user.email
        })
        .catch()
    },
    make_complaint() {
      this.complaint_loader = true
      axios
        .post('/set_product_feedback_by_gest/add_feedback_complaint/', {
          feedback_id: this.complaint_feedback_id,
          feedback_complaint: this.selected_feedback_complaint,
          email: this.complainter_email,
        })
        .then(response => {
          this.is_show_modal = false
          this.selected_feedback_complaint = 'Hostile remarks'
        })
        .catch()
        .finally(() => this.complaint_loader = false)
    }
  },
  emits: ['restart']
}
</script>

