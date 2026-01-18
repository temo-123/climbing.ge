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
                <div class="col-md-12">
                    <div class="tabs">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <!-- <div class="col-md-12"> -->
                                        <div class="col-md-3">
                                            <input type="radio" :id="1" :value="1" v-model="action_tab" />

                                            <label :for="1">Global data</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="radio" :id="3" :value="3" v-model="action_tab" />

                                            <label :for="3">Georgian data</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="radio" :id="2" :value="2" v-model="action_tab" />

                                            <label :for="2">English data</label>
                                        </div>
                                        <div class="col-md-3">
                                            <!-- <div class="col"> -->
                                            <input type="radio" :id="5" :value="5" v-model="action_tab" />

                                            <label :for="5">General info</label>
                                            <!-- </div> -->
                                        </div>
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- <input type="radio" name="tabs" id="1" checked="checked">
                        <label for="1">Global Data</label> -->
                        <span v-if="action_tab == 1">
                            <div class="row edit_buttom">
                                <div class="col-md-6">
                                    <router-link class="btn btn-primary" :to="{ name: 'siteGlobalDataEdit' }">Edit Global data</router-link>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-success float-right" @click="get_site_data" >Update</button>
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
                                        <td>{{ site_info && site_info.email ? site_info.email : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>|</td>
                                        <td>{{ site_info && site_info.number ? site_info.number : '' }}</td>
                                    </tr>

                                    <tr>
                                        <td>Map</td>
                                        <td>|</td>
                                        <td>
                                            <span v-html="site_info && site_info.map ? site_info.map : ''"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>AD block</td>
                                        <td>|</td>
                                        <td>
                                            <span v-html="site_info && site_info.ad ? site_info.ad : ''"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <p>Social networks</p>

                            <div class="row edit_buttom">
                                <div class="col-md-6">
                                    <a class="btn btn-primary pull-left" @click="open_add_link_modal()">Add Social link</a>
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
                                    <tr v-for="link in site_social_links" :key="link.id || link.url">
                                        <td v-if="link && link.title"><a :href="link && link.url ? link.url : '#'" target="_blank">{{ link.title }}</a></td>
                                        <td v-else><a :href="link && link.url ? link.url : '#'" target="_blank">{{ from_user_site_url_get_domen(link && link.url ? link.url : '') }}</a></td>
                                        <td>|</td>
                                        <td>
                                        <button class="btn btn-danger" @click="del_social_link(link.id)">Delete</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </span>

                        <!-- <input type="radio" name="tabs" id="2">
                        <label for="2">English Data</label> -->
                        <span v-else-if="action_tab == 2">
                            <div class="row edit_buttom">
                                <div class="col-md-6">
                                    <button class="btn btn-primary" @click="open_add_site_local_data_modal()">Add new slag item</button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-success float-right" @click="get_site_data" >Update</button>
                                </div>
                            </div>

                            <table class="table table-hover" id="dev-table" >
                                <thead>
                                    <tr>
                                        <th>Value name</th>
                                        <th>Data</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="info in site_us_info" :key="info.id || info.slug">
                                        <td>{{ info && info.slug ? info.slug : '' }}</td>
                                        <td v-html="info && info.us_data ? info.us_data : ''"></td>
                                        <td><button class="btn btn-primary pull-right" @click="open_edit_modal(info, 'us')">Edit</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </span>

                        <span v-else-if="action_tab == 3">
                            <div class="row edit_buttom">
                                <div class="col-md-6">
                                    <button class="btn btn-primary" @click="open_add_site_local_data_modal()">Add new slag item</button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-success float-right" @click="get_site_data" >Update</button>
                                </div>
                            </div>

                            <table class="table table-hover" id="dev-table" >
                                <thead>
                                    <tr>
                                        <th>Value name</th>
                                        <th>Data</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="info in site_ka_info" :key="info.id || info.slug">
                                        <td>{{ info && info.slug ? info.slug : '' }}</td>
                                        <td v-html="info && info.ka_data ? info.ka_data : ''"></td>
                                        <td><button class="btn btn-primary pull-right" @click="open_edit_modal(info, 'ka')">Edit</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </span>

                        <span v-else-if="action_tab == 5">
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
                            <div class="row">
                                <div class="col-md-12">
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
                                                <th>{{general_info && general_info.id ? general_info.id : ''}}</th>

                                                <th>|</th>
                                                <td>{{general_info && general_info.title ? general_info.title : ''}}</td>

                                                <th>|</th>
                                                <td>
                                                    <router-link class="btn btn-primary" :to="{ name: 'GlobalInfoEdit', params: { id: general_info && general_info.id ? general_info.id : 0 } }" >Edit</router-link></td>
                                                
                                                <td>|</td>
                                                <td>
                                                    <button type="submit" class="btn btn-danger" @click="del_general_info(general_info && general_info.id ? general_info.id : 0)">Delete</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <add_site_local_data_modal
            ref="add_site_local_data_modal"

            @update="get_site_data"
        />
        <edit_site_local_data_modal
            ref="edit_site_local_data_modal"

            @update="get_site_data"
        />

        <add_link_modal
            ref="add_link_modal"

            @update="get_social_links"
        />
        
    </div>
</template>

<script>
    // import localeInfoForm from './SiteData/tabs/LocaleDataTabComponent.vue'

    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import breadcrumb from '../../items/BreadcrumbComponent.vue'

    // import validator_alerts_component from '../../items/validator_alerts_component.vue'
// import { info } from 'cli';

    import add_link_modal from './SiteData/Modals/SiteSocialLinks/AddLinkModal.vue'
    import add_site_local_data_modal from './SiteData/Modals/SiteLocaleInfo/AddSiteLocaleInfoComponent.vue'
    import edit_site_local_data_modal from './SiteData/Modals/SiteLocaleInfo/EditSiteLocaleInfoComponent.vue'

    export default {
        components: {
            // localeInfoForm,
            // StackModal,
            // SlickItem,
            // SlickList,
            breadcrumb,

            // validator_alerts_component

            add_link_modal,
            add_site_local_data_modal,
            edit_site_local_data_modal,
        },
        // inject:[siteData],
        props: [
            //
        ],
        data() {
            return {
                site_info: [],
                site_ka_info: [],
                // site_ru_info: [],
                site_us_info: [],

                general_infos: [],
                is_general_info_refresh: false,
                general_info_reset_id: 0,

                site_social_links: [],
                is_social_links_refresh: false,

                showAddLinkModal: false,

                action_tab: 1
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
                // this.get_site_ru_data()
            },

            get_site_global_data(){
                axios
                .get('/get_site_data/get_site_global_data')
                .then(response => {
                    this.site_info = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_site_ka_data(){
                axios
                .get('/get_site_data/get_site_locale_data/ka')
                .then(response => {
                    this.site_ka_info = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_site_us_data(){
                axios
                .get('/get_site_data/get_site_locale_data/us')
                .then(response => {
                    this.site_us_info = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },


            get_social_links(){
                axios
                .get('/get_site_social_links/get_site_social_links')
                .then(response => {
                    this.site_social_links = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            // add_social_link(){
            //     axios
            //     .post('/set_site_social_links/add_site_social_links', {        
            //         data: this.form_data,
            //         _method: 'post'
            //     })
            //     .then(response => {
            //         // Success - refresh the list and close modal
            //         this.get_social_links()
            //         this.close_social_link_add_model()
            //     })
            //     .catch(err => {
            //         if (err.response && err.response.status == 422) {
            //             this.error = err.response.data.validation
            //         } else {
            //             console.log(err);
            //         }
            //     })
            // },
            // social_link_add_model(){
            //     this.is_add_social_link_model = true
            // },
            // close_social_link_add_model(){
            //     this.is_add_social_link_model = false
            //     this.form_data = []
            // },
            del_social_link(link_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/set_site_social_links/del_site_social_links/'+link_id, {
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
                .get('/get_general_info/get_all_general_infos')
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
                    .post('/set_general_info/del_general_info/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_general_info()
                    })
                    .catch(error => console.log(error))
                }
            },

            // Method to open edit modal with specific data
            open_edit_modal(item, language){
                this.$refs.edit_site_local_data_modal.show_modal_with_data(item, language)
            },

            // Method to open add link modal
            open_add_link_modal(){
                if (this.$refs.add_link_modal) {
                    this.$refs.add_link_modal.show_modal()
                }
            },

            // Method to open add site local data modal
            open_add_site_local_data_modal(){
                if (this.$refs.add_site_local_data_modal) {
                    this.$refs.add_site_local_data_modal.show_modal()
                }
            }
        }
    }
</script>
