<template>
    <div class="row">
        <!-- <div class="col-sm-3"> -->
            <left-menu />
        <!-- </div> -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="tabs">
                    <div class="row">
                        
                        <input type="radio" name="tabs" id="1" checked="checked">
                        <label for="1">Global Data</label>
                        <div class="tab">
                            <div class="row edit_buttom">
                                <div class="col-md-6">
                                    <router-link class="btn btn-primary" :to="{ name: 'siteGlobalDataEdit' }">Edit Global data</router-link>
                                </div>
                                <div class="col-md-6">
                                    <router-link class="btn btn-success float-right" :to="{ name: 'siteDataEdit' }">Edit all sites data</router-link>
                                </div>
                            </div>
                            <table class="table table-hover" id="dev-table" >
                                <thead>
                                    <tr>
                                        <th>Value name</th>
                                        <th>#</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Email</td>
                                        <td>|</td>
                                        <td>{{site_info.email}}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>|</td>
                                        <td>{{site_info.number}}</td>
                                    </tr>

                                    <tr>
                                        <td>Map</td>
                                        <td>|</td>
                                        <td>
                                            {{site_info.map}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Map</td>
                                        <td>|</td>
                                        <td>
                                            <span v-html="site_info.map"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <p>Social networks</p>

                            <div class="row edit_buttom">
                                <div class="col-md-6">
                                    <a class="btn btn-primary pull-left" @click="social_link_add_model()">Add Social link</a>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-groupe">
                                        <button @click="get_social_links()" class="btn btn-success float-right" v-if="!is_social_links_refresh">Refresh</button>
                                        <span class="badge badge-primare mb-1 float-right" v-if="is_social_links_refresh">Updating...</span>
                                    </div>
                                </div>
                            </div>

                            <table class="table table-hover" id="dev-table">
                                <thead>
                                    <tr>
                                        <th>Link</th>
                                        <th>#</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="link in site_social_links" :key="link.id">
                                        <td v-if="link.title"><a :href="link.url" target="_blank">{{ link.title }}</a></td>
                                        <td v-else><a :href="link.url" target="_blank">{{ from_user_site_url_get_domen(link.url) }}</a></td>
                                        <td>|</td>
                                        <td>
                                        <button class="btn btn-danger" @click="del_social_link(link.id)">Delete</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <input type="radio" name="tabs" id="2">
                        <label for="2">English Data</label>
                        <div class="tab">
                            <div class="row edit_buttom">
                                <div class="col-md-6">
                                    <!-- <button class="btn btn-primary" >Edit English data</button> -->
                                    <router-link class="btn btn-primary pull-left" :to="{ name: 'siteUsDataEdit' }">Edit English data</router-link>
                                </div>
                            </div>
                            <localeInfoForm :info = site_us_info />
                        </div>

                        <input type="radio" name="tabs" id="3">
                        <label for="3">Russion Data</label>
                        <div class="tab">
                            <div class="row edit_buttom">
                                <div class="col-md-6">
                                    <!-- <button class="btn btn-primary" >Russion data</button> -->
                                    <router-link class="btn btn-primary pull-left" :to="{ name: 'siteRuDataEdit' }">Edit Russion data</router-link>
                                </div>
                            </div>
                            <localeInfoForm :info = site_ru_info />
                        </div>

                        <input type="radio" name="tabs" id="4">
                        <label for="4">Georgian Data</label>
                        <div class="tab">
                            <div class="row edit_buttom">
                                <div class="col-md-6">
                                    <!-- <button class="btn btn-primary" >Georgian data</button> -->
                                    <router-link class="btn btn-primary pull-left" :to="{ name: 'siteKaDataEdit' }">Edit Georgian data</router-link>
                                </div>
                            </div>
                            <localeInfoForm :info = site_ka_info />
                        </div>


                        <input type="radio" name="tabs" id="5">
                        <label for="5">General info</label>
                        <div class="tab">
                            <div class="row edit_buttom">
                                <div class="col-md-6">
                                    <router-link class="btn btn-primary pull-left" :to="{ name: 'GlobalInfoAdd' }">Add General info</router-link>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-groupe">
                                        <button @click="get_general_info()" class="btn btn-success float-right" v-if="!is_general_info_refresh">Refresh ({{general_info_reset_id}})</button>
                                        <span class="badge badge-primare mb-1 float-right" v-if="is_general_info_refresh">Updating...</span>
                                    </div>
                                </div>
                            </div>
                            <table  class="table table-hover" id="dev-table" >
                                <thead>
                                    <tr>
                                        <th>Id</th>

                                        <th>|</th>
                                        <th>Demo title</th>

                                        <th>|</th>
                                        <th>edit</th>

                                        <th>|</th>
                                        <th>del</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="general_info in general_infos" :key="general_info.id">
                                        <th>{{general_info.id}}</th>

                                        <th>|</th>
                                        <td>{{general_info.title}}</td>

                                        <th>|</th>
                                        <td>
                                            <router-link class="btn btn-primary" :to="{ name: 'GlobalInfoEdit', params: { id: general_info.id } }" >Edit</router-link>
                                            <!-- <a  class="btn btn-primary" type="submit">Edit</a> -->
                                            <!-- <a :href="table_1_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a> -->
                                        </td>
                                        
                                        <td>|</td>
                                        <td>
                                            <button type="submit" class="btn btn-danger" @click="del_social_link(general_info.id)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <stack-modal
                :show="is_add_social_link_model"
                title="Add site social link"
                @close="close_social_link_add_model"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form method="POST" id="add_social_link_form" v-on:submit.prevent="add_social_link">
                    <input type="text" class="form-control" v-model="form_data.title" name="title" id="Title" placeholder="Title" title="Title">
                    <input type="url" class="form-control" v-model="form_data.url" name="url" id="url" pattern="https://.*|http://.*" placeholder="URL" required>
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="add_social_link_form"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import localeInfoForm from './SiteData/tabs/LocaleDataTabComponent.vue'

    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            localeInfoForm,
            StackModal,
            SlickItem,
            SlickList,
            breadcrumb
        },
        inject:['siteData'],
        props: [
            //
        ],
        data() {
            return {
                site_info: [],
                site_ka_info: [],
                site_ru_info: [],
                site_us_info: [],

                general_infos: [],
                is_general_info_refresh: false,
                general_info_reset_id: 0,

                site_social_links: [],
                is_general_info_refresh: false,
                is_social_links_refresh: false,

                is_add_social_link_model: false,

                form_data: {
                    title: '',
                    url: ''
                }
            }
        },
        mounted() {
            this.get_site_data()
            this.get_general_info()
            this.get_social_links()
        },
        methods: {
            from_user_site_url_get_domen(url){
                return new URL(url).hostname;
            },

            get_site_data: function(){
                this.get_site_global_data()
                this.get_site_ka_data()
                this.get_site_us_data()
                this.get_site_ru_data()
            },

            get_site_global_data(){
                axios
                .get('../../api/siteData/get_site_global_data')
                .then(response => {
                    this.site_info = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_site_ka_data(){
                axios
                .get('../../api/siteData/get_site_ka_data')
                .then(response => {
                    this.site_ka_info = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_site_ru_data(){
                axios
                .get('../../api/siteData/get_site_ru_data')
                .then(response => {
                    this.site_ru_info = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_site_us_data(){
                axios
                .get('../../api/siteData/get_site_us_data')
                .then(response => {
                    this.site_us_info = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },


            get_social_links(){
                axios
                .get('/siteData/site_social_links')
                .then(response => {
                    this.site_social_links = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            add_social_link(){
                axios
                .post('/siteData/site_social_links', {        
                    data: this.form_data,

                    _method: 'post'
                })
                .then(response => {
                    this.get_social_links()
                    this.close_social_link_add_model()
                })
                .catch(err => {
                    console.log(err);
                })
            },
            social_link_add_model(){
                this.is_add_social_link_model = true
            },
            close_social_link_add_model(){
                this.is_add_social_link_model = false
                this.form_data = []
            },
            del_social_link(link_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../api/siteData/site_social_links/'+link_id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_social_links()
                    })
                    .catch(error => console.log(error))
                }
            },

            get_general_info: function(){
                axios
                .get('/general_info')
                .then(response => {
                    this.general_infos = response.data
                    this.general_info_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },
            del_general_info(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../api/general_info/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_general_info()
                    })
                    .catch(error => console.log(error))
                }
            }
        }
    }
</script>

<style scoped>
    .edit_buttom{
        margin-bottom: 2%;
    }
.tabs {
    /* display: flex; */
    flex-wrap: wrap;
}
.tabs label {
    order: 1;
    display: block;
    padding: 1rem 2rem;
    margin-right: 0.2rem;
    cursor: pointer;
    background: #ccced0;
    font-weight: bold;
    transition: background ease 0.2s;
}
.tabs .tab {
    order: 99;
    flex-grow: 1;
    width: 100%;
    display: none;
    padding: 1rem;
    background: #fff;
    /* border: 1px solid #ccc!important; */
}
.tabs input[type="radio"] {
    display: none;
}
.tabs input[type="radio"]:checked + label {
    background: #fff;
    border: 1px solid #ccc !important;
}
.tabs input[type="radio"]:checked + label + .tab {
    display: block;
}

@media (max-width: 45em) {
    .tabs .tab,
    .tabs label {
        order: initial;
    }
    .tabs label {
        width: 100%;
        margin-right: 0;
        margin-top: 0.2rem;
    }
}
</style>
