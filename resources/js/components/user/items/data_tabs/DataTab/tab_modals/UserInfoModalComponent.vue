<template>
    <stack-modal
            :show="user_modal"
            title="User info"
            @close="user_modal=false"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <h1>{{ user.name }} {{ user.surname }}</h1>
            <p>{{ user.country }}, {{ user.city }}</p>
            <p>{{ user.phone_number }}</p>
            <p>{{ user.email }}</p>

            <span v-if="user.email_verified_at != null">Verification complited</span>
            <span v-if="user.email_verified_at == null">Verification feild</span>

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
                user: [],
            }
        },

        mounted() {
            
        },

        methods: {
            show_user_info(user_info){
                this.user = user_info
                this.user_modal = true
            },
        }
    }
</script>

<style>

</style>