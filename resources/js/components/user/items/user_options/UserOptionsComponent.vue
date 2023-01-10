<template>
    <div class="col-md-12" >
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 col-sm-12 img">
                    <img v-if="this.user.image == null" :src="'/public/images/site_img/user_demo_img.gif'" class="rounded mx-auto d-block user_demo_img"  :alt="this.user.name">
                    <img v-else :src="'/public/images/user_profil_img/' + this.user.image" class="rounded mx-auto d-block"  :alt="this.user.name">
                    <p class="user_image_update_bot" @click="open_edit_image_modal()">Edit image</p>
                </div>
                <div class="col-md-6 col-sm-12 details">
                    <p>Name - {{this.user.name}}</p>
                    <p>Surname - {{this.user.surname}}</p>

                    <p>Email - {{this.user.email}}</p>
                    <p>phone - {{this.user.phone_number}}</p>

                    <p>Country - {{this.user.country}}</p>
                    <p>City - {{this.user.city}}</p>

                    <!-- <p>Your Status = {{this.status}}</p> -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-groupe">
                                <button @click="open_edit_modal()" class="btn btn-primary">Edit my data</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-groupe">
                                <button @click="get_user_data" class="btn btn-success float-right" v-if="!user_is_refresh">Refresh ({{user_reset_id}})</button>
                                <span class="badge badge-primare mb-1 float-right" v-if="user_is_refresh">Updating...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button @click="edit_password_model()" class="btn btn-primary">Edit my passeord</button>
                    </div>
                </div>
            </div>
        <!-- </div> -->
            <div class="row">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button class="btn btn-primary" @click="new_user_site_add_model()" >Add new site</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button @click="get_user_sites" class="btn btn-success float-right" v-if="!is_user_site_refresh">Refresh ({{user_site_reset_id}})</button>
                        <span class="badge badge-primare mb-1 float-right" v-if="is_user_site_refresh">Updating...</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>Site</th>
                                <th>|</th>
                                <th>Edit</th>
                                <th>|</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr v-for="site in user_sites" :key="site.id">
                                <td @click="go_to_user_site(site.url)" class="cursor_pointre">{{ from_user_site_url_get_domen(site.url) }}</td>
                                <td>|</td>
                                <td>
                                    <button class="btn btn-primary" @click="get_editing_user_site(site.id)">Edit</button>
                                </td>
                                <td>|</td>
                                <td>
                                    <button class="btn btn-danger" @click="del_user_site(site.id)">Del</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <stack-modal
                :show="is_add_user_site"
                title="Add your site"
                @close="is_add_user_site=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form class="form" method="POST" id="sdd_site" v-on:submit.prevent="add_user_site">
                    <label for="url">Enter an https:// URL:</label>

                    <input type="url" class="form-control" name="url" v-model="user_site_form_data.url" id="url" pattern="https://.*|http://.*" required>
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="sdd_site"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_edit_user_site"
                title="Edit your site"
                @close="close_edit_modal()"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form class="form" method="POST" id="edit_site" v-on:submit.prevent="edit_user_site">
                    <label for="url">Enter an https:// URL:</label>

                    <input type="url" class="form-control" name="url" v-model="user_site_form_data.url" id="url" pattern="https://.*|http://.*" placeholder="URL" required>
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="edit_site"
                    >
                    update
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_edit_data"
                title="Edit user data"
                @close="is_edit_data=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form class="form" method="POST" id="registrationForm">

                    <input type="text" class="form-control" v-model="edit_data.name" name="name" placeholder="name" title="enter your name">
                    <input type="text" class="form-control" v-model="edit_data.surname" name="surname" id="surname" placeholder="last name" title="enter your last name">
                    <input type="text" class="form-control" v-model="edit_data.phone_number" name="phone_number" id="phone_number" placeholder="enter phone number" title="enter your phone number">
                    <input type="email" class="form-control" v-model="edit_data.email" name="email" id="email" placeholder="email" title="enter your email.">
                    <input type="text" class="form-control" v-model="edit_data.city" name="city" id="city" placeholder="city" title="enter your city.">
                    <input type="text" class="form-control" v-model="edit_data.country" name="country" id="country" placeholder="country" title="enter a country.">

                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="user_data_update()"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_change_image"
                title="Edit image"
                @close="is_change_image=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">

                <img v-if="user.image == null" :src="'/public/images/site_img/user_demo_img.gif'" class="rounded mx-auto d-block user_demo_img"  :alt="this.user.name">
                <img v-if="user.image"  :src="'/public/images/user_profil_img/' + this.user.image" class="rounded mx-auto d-block"  :alt="this.user.name">

                <form ref="myForm" v-on:submit.prevent="edit_image" id="profil_image_form">
                    <div class="container">
                        <div class="form-group clearfix row">
                            <input type="file" name="image" id="image" value="image">
                        </div>
                    </div>
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="profil_image_form"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_change_password"
                title="Edit password"
                @close="is_change_password=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form class="form" method="POST" id="registrationForm">
                    <input type="text" class="form-control" name="Olde password" placeholder="Olde password" title="Olde password">
                    <hr>
                    <input type="text" class="form-control" name="New password" placeholder="New password" title="New password">
                    <input type="text" class="form-control" name="Repeat new password" placeholder="Repeat new password" title="Repeat new password">
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_password()"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

    </div>
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
                tab_num: 1,

                user: [],
                user_is_refresh: false,
                user_reset_id: 0,

                is_edit_data: false,
                is_change_image: false,
                is_change_password: false,

                edit_data: {
                    name: '',
                    surname: '',
                    phone_number: '',
                    email: '',
                    city: '',
                    country: '',
                },

                user_site_form_data: {
                    url: ''
                },

                user_sites: [],

                is_user_site_refresh: false,
                user_site_reset_id: 0,

                is_add_user_site: false,
                is_edit_user_site: false,

                editing_site_id: 0
            }
        },
        mounted() {
            this.get_user_data()
        },

        methods: {
            get_user_data: function(){
                this.user_is_refresh = true
                axios
                .get("./api/options/get_user_data/")
                .then(response => {
                    this.user = response.data

                    this.user_is_refresh = false
                    this.user_reset_id++

                    this.get_user_sites()
                })
                .catch(
                    error => console.log(error)
                );
            },
            user_data_update() {
                axios
                .post('../api/options/user_info_update/' + this.user.id, {
                    data: this.edit_data,
                })
                .then(Response => {
                    this.get_user_data()

                    this.edit_data = {
                        name: '',
                        surname: '',
                        phone_number: '',
                        email: '',
                        city: '',
                        country: '',
                    }

                    this.is_edit_data = false
                })
                .catch(error => console.log(error))
            },

            edit_password_model(){
                this.is_change_password = true
            },

            edit_password(){
                this.is_change_password = false
                alert('update password')
            },

            edit_image(){
                this.is_change_image = false
                // alert('update image')

                var myFormData = new FormData(this.$refs.myForm)
                axios({
                    method: 'post',
                    url: './api/user_image_update/'+this.user.id,
                    data: myFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    // this.is_add_image = false
                    this.get_user_data()
                });
            },

            open_edit_modal() {
                this.edit_data = {
                    name: this.user.name,
                    surname: this.user.surname,
                    phone_number: this.user.phone_number,
                    email: this.user.email,
                    city: this.user.city,
                    country: this.user.country,
                }

                this.is_edit_data = true
            },
            open_edit_image_modal() {
                this.is_change_image = true
            },

            get_user_sites() {
                axios
                .get("./api/user_site/")
                .then(response => {
                    this.user_sites = response.data

                    this.is_user_site_refresh = false
                    this.user_site_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },
            from_user_site_url_get_domen(url){
                return new URL(url).hostname;
            },
            new_user_site_add_model(){
                this.is_add_user_site = true
            },

            add_user_site(){
                axios
                .post('../api/user_site/', {
                    data: this.user_site_form_data,
                    _method: 'POST'
                })
                .then(response => {
                    this.is_add_user_site = false
                    this.user_site_form_data.url = ''
                    this.get_user_sites()
                })
                .catch(
                    //
                );
            },
            close_edit_modal(){
                this.is_edit_user_site = false
                this.editing_site_id = 0
            },
            edit_user_site(){
                axios
                .post('../api/user_site/' + this.editing_site_id, {        
                    editing_data: this.user_site_form_data,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.is_edit_user_site = false
                    this.get_user_sites()
                    this.editing_site_id = 0
                })
                .catch(
                    //
                );
            },
            get_editing_user_site(site_id){
                axios
                .get("../api/user_site/"+site_id)
                .then(response => {
                    this.user_site_form_data.url = response.data.url
                    this.is_edit_user_site = true
                    this.editing_site_id = site_id
                })
                .catch(
                    //
                );
            },
            del_user_site(site_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/user_site/'+site_id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_user_sites()
                    })
                    .catch(error => {
                        //
                    })
                }
            },
            go_to_user_site(url){
                window.open(url, '_blank');
            }
        }
}
</script>

<style>
    .user_image_update_bot{
        text-align: center;
        cursor: pointer;
    }
</style>