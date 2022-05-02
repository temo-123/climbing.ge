<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        
                        <label for="1" >Notifications</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        
                        <label for="2" >Content counts</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >Meils</label>
                    </div>
                </div>
            </div>

            <div class="row" v-if="tab_num == 1">
                <div class="col-md-12" >
                    <div class="justify-content-center">
                        <div class="card" v-if="
                                !this.$siteData['text'] ||
                                !this.$siteData['text_ru'] ||
                                !this.$siteData['text_ka'] ||
                                !this.$siteData['short_description_ru'] ||
                                !this.$siteData['short_description_ka'] ||
                                !this.$siteData['short_description']
                            ">
                            <div class="card-body">
                                <div class="alert alert-danger" role="alert">
                                    <strong>Danger!</strong> Your web-site information is not fool. check page "About us", and add missing information.
                                </div>
                            </div>
                        </div>
                        <div class="card" v-if="this.counts['global_articles_count_us_error']">
                            <div class="card-body">
                                <div class="alert alert-danger" role="alert">
                                    <strong>Danger!</strong> {{ this.counts['global_articles_count_us_error'] }} articles have empty "us_article_id" vallue. It is wery bad for site localization.
                                </div>
                            </div>
                        </div>
                        <div class="card" v-if="this.counts['global_articles_count_ka_error']">
                            <div class="card-body">
                                <div class="alert alert-danger" role="alert">
                                    <strong>Danger!</strong> {{ this.counts['global_articles_count_ka_error'] }} articles have empty "ka_article_id" vallue. It is wery bad for site localization.
                                </div>
                            </div>
                        </div>
                        <div class="card" v-if="this.counts['global_articles_count_ru_error']">
                            <div class="card-body">
                                <div class="alert alert-danger" role="alert">
                                    <strong>Danger!</strong> {{ this.counts['global_articles_count_ru_error'] }} articles have empty "ru_article_id" vallue. It is wery bad for site localization.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="tab_num == 2">
                <div class="col-md-12" >
                    <div class="card">
                        <div class="card-body">
                            <div class="col">global_articles_count - {{this.counts['global_articles_count']}}</div>
                            <div class="col">ka_articles_count - {{this.counts['ka_articles_count']}}</div>
                            <div class="col">ru_articles_count - {{this.counts['ru_articles_count']}}</div>
                            <div class="col">us_articles_count - {{this.counts['us_articles_count']}}</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="col">outdoor climbing - {{this.counts['outdoor_climbing']}}</div>
                            <div class="col">spots regions - xxxx{{this.counts['outdoor_climbing']}}</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="col">all images - {{this.counts['gallery_images']}}</div>
                            <div class="col">index header images - {{this.counts['index_header_images']}}</div>
                            <div class="col">article gallery images - {{this.counts['article_gallery_images']}}</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="col">mountaineering climbing routes - {{this.counts['mountaineering_route']}}</div>
                            <div class="col">mount masiv - {{this.counts['mount_masives']}}</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="col">indoor gyms - {{this.counts['indoor_gyms']}}</div>
                            <div class="col">ice climbing - {{this.counts['ice_climbing']}}</div>
                            <div class="col">other_antyvity - {{this.counts['other_antyvity']}}</div>
                            <div class="col">event - {{this.counts['event']}}</div>
                            <div class="col">news - {{this.counts['news']}}</div>
                            <div class="col">techtip - {{this.counts['techtip']}}</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="col">sport climbing routes - {{this.counts['sport_climbing_routes_count']}}</div>
                            <div class="col">top rope - {{this.counts['top_rope_routes_count']}}</div>
                            <div class="col">boulder - {{this.counts['bouldering_routes_count']}}</div>
                            <div class="col">tred climbing - {{this.counts['tred_routes_count']}}</div>
                            <div class="col">aid - {{this.counts['aid_routes_count']}}</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="col">sectors_count - {{this.counts['sectors_count']}}</div>
                            <div class="col">routes_count - {{this.counts['routes_count']}}</div>
                            <div class="col">mtp_count - {{this.counts['mtp_count']}}</div>
                            <div class="col">mtp_pitch_count - {{this.counts['mtp_pitch_count']}}</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="col">products - {{this.counts['products']}}</div>
                            <div class="col">product_categories - {{this.counts['product_categories']}}</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="col">users - {{this.counts['users']}}</div>
                            <div class="col">following_users - {{this.counts['following_users']}}</div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="col">roles - {{this.counts['roles']}}</div>
                            <div class="col">parmisions - {{this.counts['parmisions']}}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="tab_num == 3">
                <div class="col-md-12" >
                    <div class="card">
                        <div class="card-body">
                            mails block
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import Editor from '../items/canvas/EditorComponent.vue'
    export default {
        // components: {
        //     Editor,
        // },
        data(){
            return{
                counts: null,
                tab_num: 1,
            }
        },
        mounted(){
            this.get_site_counts()
        },
        methods: {
            get_site_counts(){
                axios
                .get('/api/site_data_counts')
                .then(response => {
                    this.counts = response.data
                })
                .catch(
                    error => console.log(error)
                );
            }
        }
    }
</script>

<style scoped>
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