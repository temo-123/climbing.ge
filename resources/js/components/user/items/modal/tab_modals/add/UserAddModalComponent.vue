<template>
    <stack-modal
            :show="is_user_add_modal"
            :title="$t('admin.users.create_new_user_title')"
            @close="close_user_add_modal()"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <form v-on:submit.prevent="add_user" id="add_user_form">
                <!-- <div class="form-group"> -->
                    <!-- <label for="email">Name</label> -->
                    <input
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': errors.name }"
                    id="name"
                    v-model="data.name"
                    :placeholder="$t('admin.users.enter_name_placeholder')"
                    required
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.name">
                    {{ errors.name[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <!-- <label for="email">surname</label> -->
                    <input
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': errors.surname }"
                    id="surname"
                    v-model="data.surname"
                    :placeholder="$t('admin.users.enter_surname_placeholder')"
                    required
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.surname">
                    {{ errors.surname[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <!-- <label for="email">Email address</label> -->
                    <input
                    type="email"
                    class="form-control"
                    :class="{ 'is-invalid': errors.email }"
                    id="email"
                    v-model="data.email"
                    :placeholder="$t('admin.users.enter_email_placeholder')"
                    required
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.email">
                    {{ errors.email[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <!-- <label for="country">country</label> -->
                    <input
                    type="country"
                    class="form-control"
                    :class="{ 'is-invalid': errors.country }"
                    id="country"
                    v-model="data.country"
                    :placeholder="$t('admin.users.enter_country_placeholder')"
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.country">
                    {{ errors.country[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <!-- <label for="city">city</label> -->
                    <input
                    type="city"
                    class="form-control"
                    :class="{ 'is-invalid': errors.city }"
                    id="city"
                    v-model="data.city"
                    :placeholder="$t('admin.users.enter_city_placeholder')"
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.city">
                    {{ errors.city[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <!-- <label for="phone_number">phone_number</label> -->
                    <input
                    type="phone_number"
                    class="form-control"
                    :class="{ 'is-invalid': errors.phone_number }"
                    id="phone_number"
                    v-model="data.phone_number"
                    :placeholder="$t('admin.users.enter_phone_number_placeholder')"
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.phone_number">
                    {{ errors.phone_number[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <!-- <label for="password">Password</label> -->
                    <input
                    type="password"
                    class="form-control"
                    :class="{ 'is-invalid': errors.password }"
                    id="password"
                    v-model="data.password"
                    :placeholder="$t('admin.users.password_placeholder')"
                    required
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.password">
                    {{ errors.password[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <!-- <label for="password_confirmation">Confirm password</label> -->

                    <div class="alert alert-danger" role="alert" v-if="is_pass_confirm_error">
                        {{ $t('admin.users.confirmed_password_incorrect') }}
                    </div>

                    <input
                    type="password"
                    class="form-control"
                    id="password_confirmation"
                    v-model="data.password_confirmation"
                    :placeholder="$t('admin.users.confirm_password_placeholder')"
                    required
                    />
                <!-- </div> -->
            </form>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="add_user_form"
                    >
                {{ $t('admin.users.create_user_btn') }}
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            // StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                is_user_add_modal: false,
                // user_new_parmission: '',
                is_pass_confirm_error: false,
                // parmision_error: [],
                modalClass: '',
                errors: [],

                data: {
                    name: '',
                    surname: '',
                    email: '',
                    phone_number: '',
                    country: '',
                    city: '',
                    password: '',
                    password_confirmation: '',
                }
            }
        },

        mounted() {
            
        },

        methods: {
            open_user_add_modal(){
                this.is_user_add_modal = true
            },
            close_user_add_modal(){
                this.is_user_add_modal = false

                this.data = {
                    name: '',
                    surname: '',
                    email: '',
                    phone_number: '',
                    country: '',
                    city: '',
                    password: '',
                    password_confirmation: '',
                }
            },
            add_user() {
                if(this.data.password == this.data.password_confirmation){
                    axios
                    .post('/get_user/create_user_by_admin',{
                        data: this.data,
                    })
                    .then(res=>{
                        this.close_user_add_modal()
                        this.update_users()
                        // alert('User create sucsesful!')
                    })
                    .catch(err => {
                        console.log(err);
                    })
                }
                else{
                    this.is_pass_confirm_error = true
                }
            },
            update_users(){
                this.$emit('restart')
            }
        }
    }
</script>

<style>

</style>