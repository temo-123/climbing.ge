<template>
    <div class="col-md-12" >
        <div class="col-md-12">
            <div v-if="user.name == null || user.surname == null || user.country == null || user.city == null || user.phone_number == null || user.email == null">
                <div :class="'alert alert-danger cursor_pointer'" @click="goTo('/options')" role="alert">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>{{ $t('common.warning') }}</strong>
                    <p>{{ $t('user.profile.incomplete_info') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12 img">
                    <img v-if="this.user.image == null" :src="'/public/images/site_img/user_demo_img.gif'" class="rounded mx-auto d-block user_demo_img"  :alt="this.user.name">
                    <img v-else :src="'/public/images/user_profil_img/' + this.user.image" class="rounded mx-auto d-block"  :alt="this.user.name">
                    <p class="user_image_update_bot" @click="open_edit_image_modal()">{{ $t('user.profile.edit_image') }}</p>
                </div>
                <div class="col-md-6 col-sm-12 details">
                    <p>{{ $t('user.profile.label_name', { value: this.user.name }) }}</p>
                    <p>{{ $t('user.profile.label_surname', { value: this.user.surname }) }}</p>

                    <p>{{ $t('user.profile.label_email', { value: this.user.email }) }}</p>
                    <p>{{ $t('user.profile.label_phone', { value: this.user.phone_number }) }}</p>

                    <p>{{ $t('user.profile.label_country', { value: this.user.country }) }}</p>
                    <p>{{ $t('user.profile.label_city', { value: this.user.city }) }}</p>
                    <p>{{ $t('user.profile.label_language', { value: this.user.lang === 'ka' ? $t('user.profile.lang_georgian') : $t('user.profile.lang_english') }) }}</p>
                    <p v-if="user.my_bio">{{ $t('user.profile.label_bio') }}: {{ user.my_bio }}</p>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-groupe">
                                <button @click="open_edit_modal()" class="btn btn-primary float-right">{{ $t('user.profile.edit_my_data') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-groupe">
                                <button @click="get_user_data" class="btn btn-success float-right" v-if="!user_is_refresh">{{ $t('common.refresh') }} ({{user_reset_id}})</button>
                                <span class="badge badge-primare mb-1 float-right" v-if="user_is_refresh">{{ $t('common.updating') }}</span>
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
                            <button @click="edit_password_model()" class="btn btn-primary">{{ $t('user.profile.edit_password_btn') }}</button>
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
                        <button class="btn btn-primary" @click="new_user_site_add_model()" >{{ $t('user.profile.add_new_site') }}</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-groupe">
                        <button @click="get_user_sites" class="btn btn-success float-right" v-if="!is_user_site_refresh">{{ $t('common.refresh') }} ({{user_site_reset_id}})</button>
                        <span class="badge badge-primare mb-1 float-right" v-if="is_user_site_refresh">{{ $t('common.updating') }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 data_tab">
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                        <th>{{ $t('user.profile.col_site') }}</th>
                        <th></th>
                        <th>{{ $t('common.edit') }}</th>
                        <th></th>
                        <th>{{ $t('common.delete') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="site in user_sites" :key="site.id">
                                <td @click="go_to_user_site(site.url)"><p class="cursor_pointer"><strong>{{ from_user_site_url_get_domen(site.url) }}</strong></p></td>
                                <td></td>
                                <td>
                                    <button class="btn btn-primary" @click="get_editing_user_site(site.id)">{{ $t('common.edit') }}</button>
                                </td>
                                <td></td>
                                <td>
                                    <button class="btn btn-danger" @click="del_user_site(site.id)">{{ $t('common.delete') }}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <StackModal
                v-model="is_add_user_site"
                :title="$t('user.profile.add_site_title')"
                @close="is_add_user_site=false"
                @save="$refs.add_user_site_form.requestSubmit()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
            >
            <div>
                <form ref="add_user_site_form" class="form" method="POST" id="sdd_site" v-on:submit.prevent="add_user_site">
                    <label for="url">{{ $t('user.profile.enter_url_label') }}</label>

                    <input type="url" class="form-control" name="url" v-model="user_site_form_data.url" id="url" pattern="https://.*|http://.*" :placeholder="$t('user.profile.enter_url_placeholder')" required>
                </form>
            </div>
        </StackModal>

        <StackModal
                v-model="is_edit_user_site"
                :title="$t('user.profile.edit_site_title')"
                @close="close_edit_modal()"
                @save="$refs.edit_site_form.requestSubmit()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
            >

            <div>
                <form ref="edit_site_form" class="form" method="POST" id="edit_site" v-on:submit.prevent="edit_user_site">
                    <label for="url">{{ $t('user.profile.enter_url_label') }}</label>

                    <input type="url" class="form-control" name="url" v-model="user_site_form_data.url" id="url" pattern="https://.*|http://.*" :placeholder="$t('user.profile.enter_url_placeholder')" required>
                </form>
            </div>
        </StackModal>

        <StackModal
                v-model="is_edit_data"
                :title="$t('user.profile.edit_data_title')"
                @close="is_edit_data=false"
                @save="$refs.registrationForm.requestSubmit()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
            >

            <div>
                <form ref="registrationForm" class="form" method="POST" id="registrationForm" v-on:submit.prevent="user_data_update">

                    <input type="text" class="form-control" v-model="edit_data.name" name="name" :placeholder="$t('user.profile.name_placeholder')" :title="$t('user.profile.name_placeholder')">
                    <input type="text" class="form-control" v-model="edit_data.surname" name="surname" id="surname" :placeholder="$t('user.profile.surname_placeholder')" :title="$t('user.profile.surname_placeholder')">
                    <input type="tel" class="form-control" v-model="edit_data.phone_number" name="phone_number" id="phone_number" :placeholder="$t('user.profile.phone_placeholder')" :title="$t('user.profile.phone_placeholder')">
                    <input type="email" class="form-control" v-model="edit_data.email" name="email" id="email" :placeholder="$t('user.profile.email_placeholder')" :title="$t('user.profile.email_placeholder')">
                    <input type="text" class="form-control" v-model="edit_data.city" name="city" id="city" :placeholder="$t('user.profile.city_placeholder')" :title="$t('user.profile.city_placeholder')">
                    <input type="text" class="form-control" v-model="edit_data.country" name="country" id="country" :placeholder="$t('user.profile.country_placeholder')" :title="$t('user.profile.country_placeholder')">

                    <select class="form-control" v-model="edit_data.lang" name="lang" id="lang" :title="$t('user.profile.select_language_title')">
                        <option value="us">{{ $t('user.profile.lang_english') }}</option>
                        <option value="ka">{{ $t('user.profile.lang_georgian') }}</option>
                    </select>

                    <label for="my_bio">{{ $t('user.profile.bio_label') }}</label>
                    <textarea class="form-control" v-model="edit_data.my_bio" name="my_bio" id="my_bio" rows="4" :placeholder="$t('user.profile.bio_placeholder')" :title="$t('user.profile.bio_placeholder')"></textarea>

                    <label class="mt-2">{{ $t('user.profile.social_links_label') }}</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="fa fa-facebook" aria-hidden="true"></i></span>
                        <input type="url" class="form-control" v-model="edit_data.social_links.facebook" name="facebook" :placeholder="$t('user.profile.facebook_placeholder')">
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="fa fa-instagram" aria-hidden="true"></i></span>
                        <input type="url" class="form-control" v-model="edit_data.social_links.instagram" name="instagram" :placeholder="$t('user.profile.instagram_placeholder')">
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="fa fa-youtube-play" aria-hidden="true"></i></span>
                        <input type="url" class="form-control" v-model="edit_data.social_links.youtube" name="youtube" :placeholder="$t('user.profile.youtube_placeholder')">
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="fa fa-globe" aria-hidden="true"></i></span>
                        <input type="url" class="form-control" v-model="edit_data.social_links.website" name="website" :placeholder="$t('user.profile.website_placeholder')">
                    </div>
                </form>
            </div>
        </StackModal>

        <StackModal
                :show="is_change_image"
                :title="$t('user.profile.edit_image_title')"
                @close="is_change_image=false"
                @save="$refs.myForm.requestSubmit()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
            >
            <div>

                <form ref="myForm" v-on:submit.prevent="edit_image" id="profil_image_form">
                    <div class="container">
                        <div class="form-group clearfix row">
                            <single_image_edit
                                :key="(user.id || 0) + '-' + image_reset_id"
                                :title_prop="''"
                                :crop_ratio_prop="{ width: 1, height: 1 }"
                                :existing_image_url_prop="user.image ? ('/public/images/user_profil_img/' + user.image) : ''"
                                @update_single_image="profile_image_file = $event"
                                @processing="profile_image_processing = $event"
                            />
                        </div>
                    </div>
                </form>
            </div>
        </StackModal>

        <StackModal
                :show="is_change_password"
                :title="$t('user.profile.edit_password_title')"
                @close="close_edit_password_model()"
                @save="$refs.edit_password_form.requestSubmit()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
            >
            <div>
                <form ref="edit_password_form" class="form" method="POST" id="edit_password" v-on:submit.prevent="edit_password">
                    <div class="alert alert-danger" role="alert" v-if="is_old_pass_error">
                        {{ $t('user.profile.old_password_error') }}
                    </div>
                    <input type="password" class="form-control" name="Old password" :placeholder="$t('user.profile.old_password_placeholder')" v-model="password_edit_data.old_pass" :title="$t('user.profile.old_password_placeholder')" required>
                    <hr>
                    <input type="password" class="form-control" name="New password" :placeholder="$t('user.profile.new_password_placeholder')" v-model="password_edit_data.new_pass" :title="$t('user.profile.new_password_placeholder')" required>

                    <div class="alert alert-danger" role="alert" v-if="is_password_cofirming_error">
                        {{ $t('user.profile.password_confirm_error') }}
                    </div>
                    <input type="password" class="form-control" name="Repeat new password" :placeholder="$t('user.profile.repeat_password_placeholder')" v-model="password_edit_data.confirm_new_pass" :title="$t('user.profile.repeat_password_placeholder')" required>
                </form>
            </div>
        </StackModal>

    </div>
</template>

<script>

    import single_image_edit from '../single_image/singleImageEditComponent.vue'

    export default {
        components: { single_image_edit },
        data(){
            return {
                tab_num: 1,

                user: [],
                user_is_refresh: false,
                user_reset_id: 0,

                is_edit_data: false,
                is_change_image: false,
                profile_image_file: null,
                profile_image_processing: false,
                image_reset_id: 0,
                is_change_password: false,

                edit_data: {
                    name: '',
                    surname: '',
                    phone_number: '',
                    email: '',
                    city: '',
                    country: '',
                    lang: 'us',
                    my_bio: '',
                    social_links: {
                        facebook: '',
                        instagram: '',
                        youtube: '',
                        website: '',
                    },
                },

                password_edit_data: {
                    old_pass: '',
                    new_pass: '',
                    confirm_new_pass: '',
                },
                is_password_cofirming_error: false,
                is_old_pass_error: false,

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
                .get('auth_user')
                .then((response)=>{
                    this.user = response.data

                    this.user_is_refresh = false
                    this.user_reset_id++

                    this.get_user_sites()
                })
                .catch(
                    // this.user = 'Boss'
                );
            },
            user_data_update() {
                axios
                .post('get_options/user_info_update/' + this.user.id, {
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
                        lang: 'us',
                        my_bio: '',
                    }

                    this.is_edit_data = false
                })
                .catch(error => console.log(error))
            },

            edit_password_model(){
                this.is_change_password = true
            },

            close_edit_password_model(){
                this.is_change_password = false
                this.password_edit_data.old_pass = ''
                this.password_edit_data.new_pass = ''
                this.password_edit_data.confirm_new_pass = ''
            },

            edit_password(){
                this.is_password_cofirming_error = false
                this.is_old_pass_error = false

                if(this.password_edit_data.new_pass === this.password_edit_data.confirm_new_pass){
                    if(confirm(this.$t('user.profile.confirm_edit_password'))){
                        axios
                        .post("user/update_password",{
                            data: this.password_edit_data,
                        })
                        .then(response => {
                            if(response.data == "Old Password Doesn't match!"){
                                this.password_edit_data.old_pass = ''
                                this.is_old_pass_error = true
                            }
                            else{
                                this.close_edit_password_model()
                                alert(response.data)
                            }
                        })
                        .catch(
                            error => console.log(error)
                        );
                    }
                }
                else{
                    this.is_password_cofirming_error = true
                }
            },

            edit_image(){
                if (this.profile_image_processing) {
                    return
                }
                if (!this.profile_image_file) {
                    this.is_change_image = false
                    return
                }
                this.is_change_image = false

                var myFormData = new FormData()
                myFormData.append('image', this.profile_image_file)
                axios({
                    method: 'post',
                    url: 'user/user_image_update/'+this.user.id,
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
                    lang: this.user.lang || 'us',
                    my_bio: this.user.my_bio || '',
                    social_links: {
                        facebook: this.user.social_links?.facebook || '',
                        instagram: this.user.social_links?.instagram || '',
                        youtube: this.user.social_links?.youtube || '',
                        website: this.user.social_links?.website || '',
                    },
                }

                this.is_edit_data = true
            },
            open_edit_image_modal() {
                this.profile_image_file = null
                this.profile_image_processing = false
                this.image_reset_id++
                this.is_change_image = true
            },

            get_user_sites() {
                axios
                .get("/user_site/")
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
                .post('user_site/', {
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
                .post('user_site/' + this.editing_site_id, {        
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
                .get("/user_site/"+site_id)
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
                if(confirm(this.$t('user.profile.confirm_delete_site'))){
                    axios
                    .post('user_site/'+site_id, {
                        _method: 'DELETE'
                    })
                    .then(response => {
                        this.get_user_sites()
                    })
                    .catch(error => {
                        console.log('Error deleting site:', error)
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