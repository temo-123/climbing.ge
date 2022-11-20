<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="tabs">
                <div class="row">
                    
                    <input type="radio" name="tabs" id="1" checked="checked">
                    <label for="1">Global Data</label>
                    <div class="tab">
                        <div class="row edit_buttom">
                            <div class="col-md-6">
                                <!-- <button class="btn btn-primary" >Edit all data</button> -->
                                <router-link class="btn btn-primary pull-left" :to="{ name: 'siteInfoEdit' }">Edit all sites data</router-link>
                            </div>
                            <div class="col-md-6">
                                <!-- <button class="btn btn-primary" >Global data</button> -->
                                <router-link class="btn btn-primary pull-left" :to="{ name: 'siteGlobalDataEdit' }">Edit Global data</router-link>
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
                                <!-- <tr>
                                    <td>ID</td>
                                    <td>|</td>
                                    <td>{{site_info.id}}</td>
                                </tr> -->
                                <tr>
                                    <td>Email</td>
                                    <td>|</td>
                                    <td>{{this.$siteData.email}}</td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>|</td>
                                    <td>{{site_info.number}}</td>
                                </tr>

                                <tr>
                                    <td>Map</td>
                                    <td>|</td>
                                    <td>{{site_info.map}}</td>
                                </tr>

                                <tr>
                                    <td>Facebook</td>
                                    <td>|</td>
                                    <td>{{site_info.fb_link}}</td>
                                </tr>
                                <tr>
                                    <td>Instagram</td>
                                    <td>|</td>
                                    <td>{{site_info.inst_link}}</td>
                                </tr>
                                <tr>
                                    <td>Goolge</td>
                                    <td>|</td>
                                    <td>{{site_info.google_link}}</td>
                                </tr>
                                <tr>
                                    <td>Twitter</td>
                                    <td>|</td>
                                    <td>{{site_info.twit_link}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>article_ad_block</td>
                                    <td>|</td>
                                    <td>{{site_info.article_ad_block}}</td>
                                </tr>
                                <tr>
                                    <td>footer_ad_block</td>
                                    <td>|</td>
                                    <td>{{site_info.pages_ad_block}}</td>
                                </tr>
                                <tr>
                                    <td>pages_ad_block</td>
                                    <td>|</td>
                                    <td>{{site_info.pages_ad_block}}</td>
                                </tr>
                                <tr>
                                    <td>free_ad_block</td>
                                    <td>|</td>
                                    <td>{{site_info.free_ad_block}}</td>
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
                                    <td>Title</td>
                                    <td>|</td>
                                    <td>{{site_info.title}}</td>
                                </tr>
                                <tr>
                                    <td>Short description</td>
                                    <td>|</td>
                                    <td>{{ site_info.short_description }}</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>|</td>
                                    <td>{{site_info.text}}</td>
                                </tr>
                                <tr>
                                    <td>other_activity_description</td>
                                    <td>|</td>
                                    <td>{{site_info.other_activity_description}}</td>
                                </tr>
                                <tr>
                                    <td>mount_description</td>
                                    <td>|</td>
                                    <td>{{site_info.mount_description}}</td>
                                </tr>
                                <tr>
                                    <td>outdoor_description</td>
                                    <td>|</td>
                                    <td>{{site_info.outdoor_description}}</td>
                                </tr>
                                <tr>
                                    <td>ice_description</td>
                                    <td>|</td>
                                    <td>{{site_info.ice_description}}</td>
                                </tr>
                                <tr>
                                    <td>topo_description</td>
                                    <td>|</td>
                                    <td>{{site_info.topo_description}}</td>
                                </tr>
                                <tr>
                                    <td>what_we_do_description</td>
                                    <td>|</td>
                                    <td>{{site_info.what_we_do_description}}</td>
                                </tr>
                                <tr>
                                    <td>shop_short_description</td>
                                    <td>|</td>
                                    <td>{{site_info.shop_short_description}}</td>
                                </tr>
                                <tr>
                                    <td>shop_description</td>
                                    <td>|</td>
                                    <td>{{site_info.shop_description}}</td>
                                </tr>
                                <tr>
                                    <td>services_description</td>
                                    <td>|</td>
                                    <td>{{site_info.services_description}}</td>
                                </tr>
                                <!-- <tr>
                                    <td>meta_description</td>
                                    <td>|</td>
                                    <td>{{site_info.meta_description}}</td>
                                </tr> -->
                            </tbody>
                        </table>
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
                                    <td>Title</td>
                                    <td>|</td>
                                    <td>{{site_info.title_ru}}</td>
                                </tr>
                                <tr>
                                    <td>Short description</td>
                                    <td>|</td>
                                    <td>{{ site_info.short_description_ru}}</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>|</td>
                                    <td>{{site_info.text_ru}}</td>
                                </tr>
                                <tr>
                                    <td>other_activity_description</td>
                                    <td>|</td>
                                    <td>{{site_info.other_activity_description_ru}}</td>
                                </tr>
                                <tr>
                                    <td>mount_description</td>
                                    <td>|</td>
                                    <td>{{site_info.mount_description_ru}}</td>
                                </tr>
                                <tr>
                                    <td>outdoor_description</td>
                                    <td>|</td>
                                    <td>{{site_info.outdoor_description_ru}}</td>
                                </tr>
                                <tr>
                                    <td>ice_description</td>
                                    <td>|</td>
                                    <td>{{site_info.ice_description_ru}}</td>
                                </tr>
                                <tr>
                                    <td>topo_description</td>
                                    <td>|</td>
                                    <td>{{site_info.topo_description_ru}}</td>
                                </tr>
                                <tr>
                                    <td>what_we_do_description</td>
                                    <td>|</td>
                                    <td>{{site_info.what_we_do_description_ru}}</td>
                                </tr>
                                <tr>
                                    <td>shop_short_description</td>
                                    <td>|</td>
                                    <td>{{site_info.shop_short_description_ru}}</td>
                                </tr>
                                <tr>
                                    <td>shop_description</td>
                                    <td>|</td>
                                    <td>{{site_info.shop_description_ru}}</td>
                                </tr>
                                <tr>
                                    <td>services_description</td>
                                    <td>|</td>
                                    <td>{{site_info.services_description_ru}}</td>
                                </tr>
                                <!-- <tr>
                                    <td>meta_description</td>
                                    <td>|</td>
                                    <td>{{site_info.meta_description_ru}}</td>
                                </tr> -->
                            </tbody>
                        </table>
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
                                    <td>Title</td>
                                    <td>|</td>
                                    <td>{{site_info.title_ka}}</td>
                                </tr>
                                <tr>
                                    <td>Short description</td>
                                    <td>|</td>
                                    <td>{{ site_info.short_description_ka}}</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>|</td>
                                    <td>{{site_info.text_ka}}</td>
                                </tr>
                                <tr>
                                    <td>other_activity_description</td>
                                    <td>|</td>
                                    <td>{{site_info.other_activity_description_ka}}</td>
                                </tr>
                                <tr>
                                    <td>mount_description</td>
                                    <td>|</td>
                                    <td>{{site_info.mount_description_ka}}</td>
                                </tr>
                                <tr>
                                    <td>outdoor_description</td>
                                    <td>|</td>
                                    <td>{{site_info.outdoor_description_ka}}</td>
                                </tr>
                                <tr>
                                    <td>ice_description</td>
                                    <td>|</td>
                                    <td>{{site_info.ice_description_ka}}</td>
                                </tr>
                                <tr>
                                    <td>topo_description</td>
                                    <td>|</td>
                                    <td>{{site_info.topo_description_ka}}</td>
                                </tr>
                                <tr>
                                    <td>what_we_do_description</td>
                                    <td>|</td>
                                    <td>{{site_info.what_we_do_description_ka}}</td>
                                </tr>
                                <tr>
                                    <td>shop_short_description</td>
                                    <td>|</td>
                                    <td>{{site_info.shop_short_description_ka}}</td>
                                </tr>
                                <tr>
                                    <td>shop_description</td>
                                    <td>|</td>
                                    <td>{{site_info.shop_description_ka}}</td>
                                </tr>
                                <tr>
                                    <td>services_description</td>
                                    <td>|</td>
                                    <td>{{site_info.services_description_ka}}</td>
                                </tr>
                                <!-- <tr>
                                    <td>meta_description</td>
                                    <td>|</td>
                                    <td>{{site_info.meta_description_ka}}</td>
                                </tr> -->
                            </tbody>
                        </table>
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
                                        <button type="submit" class="btn btn-danger" @click="del_general_info(general_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        inject:['siteData'],
        props: [
        ],
        data() {
            return {
                site_info: [],
                general_infos: [],
                is_general_info_refresh: false,
                general_info_reset_id: 0
            }
        },
        components: {
        },
        mounted() {
            this.get_site_data()
            this.get_general_info()
        },
        methods: {
            get_site_data: function(){
                axios
                .get('./about/get_site_data/')
                .then(response => {
                    this.site_info = response.data.site_info[0]
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_general_info: function(){
                axios
                .get('./api/general_info')
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
