<template>
    <stack-modal
            :show="user_modal"
            title="User info"
            @close="user_modal=false"
            :modal-class="{ 'modal-lg': true }"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <h1>{{ user.name }} {{ user.surname }}</h1>

            <h4>{{ user.country }}, {{ user.city }}</h4>
            <h4>{{ user.phone_number }}</h4>
            <h4>{{ user.email }}</h4>

            <h4 v-if="user.email_verified_at != null" style="color:green;">Verification complited</h4>
            <h4 v-if="user.email_verified_at == null" style="color:red;">Verification feild</h4>

            <img v-if="user.image != null" :src="'/public/images/user_profil_img/' + user.image" class="rounded mx-auto d-block"/>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <!-- <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_permission(user_id_for_rditing_parmission)"
                    >
                Save
                </button> -->
            </div>
        </div>
    </stack-modal>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            // SlickItem,
            // SlickList,
        },
        data(){
            return {
                user_modal: false,
                // user_id: 0,
                user: [],
            }
        },

        mounted() {
            
        },

        methods: {
            show_modal(id){
                // this.user_id = id

                this.get_user(id)
            },

            get_user(user_id){
                axios
                .get("/get_options/get_selected_user_data/" + user_id)
                .then(response => {
                    this.user = response.data
                    this.user_modal = true
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>

</style>