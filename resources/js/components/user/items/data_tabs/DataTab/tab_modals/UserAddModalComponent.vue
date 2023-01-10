<template>
    <stack-modal
            :show="is_user_add_modal"
            title="Create new user"
            @close="close_user_add_modal()"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <form v-on:submit.prevent="add_user" id="add_user_form">
                <!-- <div class="form-group"> -->
                    <label for="email">Name</label>
                    <input
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': errors.name }"
                    id="name"
                    v-model="name"
                    placeholder="Enter name"
                    required
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.name">
                    {{ errors.name[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <label for="email">surname</label>
                    <input
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': errors.surname }"
                    id="surname"
                    v-model="surname"
                    placeholder="Enter surname"
                    required
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.surname">
                    {{ errors.surname[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <label for="email">Email address</label>
                    <input
                    type="email"
                    class="form-control"
                    :class="{ 'is-invalid': errors.email }"
                    id="email"
                    v-model="email"
                    placeholder="Enter email"
                    required
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.email">
                    {{ errors.email[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <label for="country">country</label>
                    <input
                    type="country"
                    class="form-control"
                    :class="{ 'is-invalid': errors.country }"
                    id="country"
                    v-model="country"
                    placeholder="Enter country"
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.country">
                    {{ errors.country[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <label for="city">city</label>
                    <input
                    type="city"
                    class="form-control"
                    :class="{ 'is-invalid': errors.city }"
                    id="city"
                    v-model="city"
                    placeholder="Enter city"
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.city">
                    {{ errors.city[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <label for="phone_number">phone_number</label>
                    <input
                    type="phone_number"
                    class="form-control"
                    :class="{ 'is-invalid': errors.phone_number }"
                    id="phone_number"
                    v-model="phone_number"
                    placeholder="Enter phone_number"
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.phone_number">
                    {{ errors.phone_number[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <label for="password">Password</label>
                    <input
                    type="password"
                    class="form-control"
                    :class="{ 'is-invalid': errors.password }"
                    id="password"
                    v-model="password"
                    placeholder="Password"
                    required
                    />
                    <!-- <div class="invalid-feedback" v-if="errors.password">
                    {{ errors.password[0] }}
                    </div> -->
                <!-- </div>
                <div class="form-group"> -->
                    <label for="password_confirmation">Confirm password</label>
                    <input
                    type="password"
                    class="form-control"
                    id="password_confirmation"
                    v-model="password_confirmation"
                    placeholder="Confirm password"
                    required
                    />
                <!-- </div> -->
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="add_user_form"
                    >
                Register user
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                is_user_add_modal: false,
                // user_new_parmission: '',
                // is_parmision_error: false,
                // parmision_error: [],
                modalClass: '',
                errors: [],
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

        mounted() {
            
        },

        methods: {
            open_user_add_modal(){
                this.is_user_add_modal = true
            },
            close_user_add_modal(){
                this.is_user_add_modal = false
            },
            add_user() {
                axios
                .post('/register',{
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    phone_number: this.phone_number,
                    country: this.country,
                    city: this.city,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                })
                .then(res=>{
                    // console.log(res);
                    // localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
                    // this.$router.push({ path: "/" });
                    this.close_user_add_modal()
                    alert('User create sucsesful!')
                    
                })
                .catch(err => {
                    console.log(err);
                })
            },

            // register(){
            // axios
            //     .get('/sanctum/csrf-cookie')
            //     .then(response => {
            //     axios
            //         .post('/register',{
            //         name: this.name,
            //         surname: this.surname,
            //         email: this.email,
            //         phone_number: this.phone_number,
            //         country: this.country,
            //         city: this.city,
            //         password: this.password,
            //         password_confirmation: this.password_confirmation
            //         })
            //         .then(res=>{
            //         // console.log(res);
            //         localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
            //         this.$router.push({ path: "/" });
            //         })
            //         .catch(err => {
            //         console.log(err);
            //         })
            //     })
            // }
        }
    }
</script>

<style>

</style>