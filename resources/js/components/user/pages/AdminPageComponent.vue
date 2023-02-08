<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
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
                                    <div class="col" >
                                        <input type="radio" id="1" :value="1" v-model="tab_num">
                                        
                                        <label for="1" >Content</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="2" :value="2" v-model="tab_num">
                                        
                                        <label for="2" >Orders</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="3" :value="3" v-model="tab_num">
                                        
                                        <label for="3" >Notifications</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="4" :value="4" v-model="tab_num">
                                        
                                        <label for="4" >Meils</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 3">
                            <div class="col-md-12" >
                                
                                <div class="alert alert-danger" role="alert" v-if="
                                        !this.$siteData['text'] ||
                                        !this.$siteData['text_ru'] ||
                                        !this.$siteData['text_ka'] ||
                                        !this.$siteData['short_description_ru'] ||
                                        !this.$siteData['short_description_ka'] ||
                                        !this.$siteData['short_description']
                                    ">
                                    <strong>Danger!</strong> 
                                    Your web-site information is not fool. check page "
                                    <router-link :to="{name: 'siteInfo'}" exact> 
                                        About us
                                    </router-link>
                                    ", and add missing information.
                                </div>
                                
                                <div class="alert alert-danger" role="alert" v-if="this.counts['global_articles_count_us_error']">
                                    <strong>Danger!</strong> {{ this.counts['global_articles_count_us_error'] }} articles have empty "us_article_id" vallue. It is wery bad for site localization.
                                </div>
                                
                                <div class="alert alert-danger" role="alert" v-if="this.counts['global_articles_count_ka_error']">
                                    <strong>Danger!</strong> {{ this.counts['global_articles_count_ka_error'] }} articles have empty "ka_article_id" vallue. It is wery bad for site localization.
                                </div>
                                
                                <div class="alert alert-danger" role="alert" v-if="this.counts['global_articles_count_ru_error']">
                                    <strong>Danger!</strong> {{ this.counts['global_articles_count_ru_error'] }} articles have empty "ru_article_id" vallue. It is wery bad for site localization.
                                </div>
                                            
                            </div>
                        </div>
                        <div class="row" v-if="tab_num == 2">
                            <div class="col-md-12" >

                                <h2>Week Orders</h2>
                                <GChart
                                    type="LineChart"
                                    :data="week_orders_data"
                                    :options="week_orders_options"
                                />

                                <h2>Year Orders</h2>
                                <GChart
                                    type="LineChart"
                                    :data="year_orders_data"
                                    :options="year_orders_options"
                                />
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 1">
                            <div class="col-md-12" >

                                <div class="alert alert-danger" role="alert" v-if="this.counts['us_articles_errors_count'] || this.counts['ru_articles_errors_count'] || this.counts['ka_articles_errors_count']">
                                    <div class="col" v-if="this.counts['us_articles_errors_count']"> 
                                        <i class="fa fa-bug" aria-hidden="true"></i> 
                                        us_articles error_count - {{this.counts['us_articles_errors_count']}} 

                                        <button class="btn btn-danger float-right" @click="quick_wiev_action(complaint.comment_id, complaint.id)">
                                            Fix bag (delete all this articles)
                                        </button>
                                    </div>
                                    <div class="col" v-if="this.counts['ru_articles_errors_count']"> 
                                        <i class="fa fa-bug" aria-hidden="true"></i> 
                                        ru_articles error_count - {{this.counts['ru_articles_errors_count']}} 

                                        <button class="btn btn-danger float-right" @click="quick_wiev_action(complaint.comment_id, complaint.id)">
                                            Fix bag (delete all this articles)
                                        </button>
                                    </div>
                                    <div class="col" v-if="this.counts['ka_articles_errors_count']"> 
                                        <i class="fa fa-bug" aria-hidden="true"></i> 
                                        ka_articles error_count - {{this.counts['ka_articles_errors_count']}} 

                                        <button class="btn btn-danger float-right" @click="quick_wiev_action(complaint.comment_id, complaint.id)">
                                            Fix bag (delete all this articles)
                                        </button>
                                    </div>
                                </div>

                                <h2>Articles</h2>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="card-counter primary">
                                                    <i class="fa fa-window-maximize" aria-hidden="true"></i>
                                                    <span class="count-numbers">{{this.counts['global_articles_count']}}</span>
                                                    <span class="count-name">Articles</span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="card-counter primary">
                                                    <i class="fa fa-language" aria-hidden="true"></i>
                                                    <span class="count-numbers">{{this.counts['us_articles_count']}}</span>
                                                    <span class="count-name">English articles</span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="card-counter primary">
                                                    <i class="fa fa-language" aria-hidden="true"></i>
                                                    <span class="count-numbers">{{this.counts['ru_articles_count']}}</span>
                                                    <span class="count-name">Russion articles</span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="card-counter primary">
                                                    <i class="fa fa-language" aria-hidden="true"></i>
                                                    <span class="count-numbers">{{this.counts['ka_articles_count']}}</span>
                                                    <span class="count-name">Georgian articles</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-4">
                                                <router-link :to="{ name: 'articlelist', params: { article_category: 'indoor' } }">
                                                    <div class="card-counter primary">
                                                        <i class="fa fa-building" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['indoor_gyms']}}</span>
                                                        <span class="count-name">Indoor gyms</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-4">
                                                <router-link :to="{ name: 'articlelist', params: { article_category: 'ice' } }">
                                                    <div class="card-counter primary">
                                                        <i class="fa fa-snowflake-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['ice_climbing']}}</span>
                                                        <span class="count-name">Ice climbing</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-4">
                                                <router-link :to="{ name: 'articlelist', params: { article_category: 'other' } }">
                                                    <div class="card-counter primary">
                                                        <i class="fa fa-bicycle" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['other_antyvity']}}</span>
                                                        <span class="count-name">Other antyvity</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                        <div class="row">

                                            <div class="col-md-4">
                                                <router-link :to="{ name: 'articlelist', params: { article_category: 'news' } }">
                                                    <div class="card-counter primary">
                                                        <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['news']}}</span>
                                                        <span class="count-name">News</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-4">
                                                <router-link :to="{ name: 'articlelist', params: { article_category: 'tech_tip' } }">
                                                    <div class="card-counter primary">
                                                        <i class="fa fa-shield" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['techtip']}}</span>
                                                        <span class="count-name">Techtip</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h2>Outdoor climbing</h2>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <router-link :to="{ name: 'articlelist', params: { article_category: 'outdoor' } }">
                                                    <div class="card-counter primary">
                                                        <i class="fa fa-window-maximize" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['outdoor_climbing']}}</span>
                                                        <span class="count-name">Outdoor climbing</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{ name: 'articlelist', params: { article_category: 'outdoor' } }">
                                                    <div class="card-counter primary">
                                                        <i class="fa fa-map-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['region']}}</span>
                                                        <span class="count-name">Spots regions</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h2>Mountaineering</h2>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                        
                                            <div class="col-md-6">
                                                <router-link :to="{ name: 'mountlist' }">
                                                    <div class="card-counter primary">
                                                        <i class="fa fa-area-chart" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['mountaineering_route']}}</span>
                                                        <span class="count-name">Mountaineering climbing routes</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{ name: 'mountlist' }">
                                                    <div class="card-counter primary">
                                                        <i class="fa fa-area-chart" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['mount_masives']}}</span>
                                                        <span class="count-name">Mount masiv</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h2>Events</h2>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                        
                                            <div class="col-md-6">
                                                <router-link :to="{ name: 'eventList' }">
                                                    <div class="card-counter success">
                                                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">XXX</span>
                                                        <span class="count-name">Active events</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{ name: 'eventList' }">
                                                    <div class="card-counter success">
                                                        <i class="fa fa-calendar-minus-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">XXX</span>
                                                        <span class="count-name">Completed events</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                        
                                            <div class="col-md-6">
                                                <router-link :to="{ name: 'eventList' }">
                                                    <div class="card-counter success">
                                                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">XXX</span>
                                                        <span class="count-name">Active competitions</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{ name: 'eventList' }">
                                                    <div class="card-counter success">
                                                        <i class="fa fa-calendar-minus-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">XXX</span>
                                                        <span class="count-name">Completed competitions</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h2>Climbing spots</h2>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                        
                                            <div class="col-md-6">
                                                <router-link :to="{name: 'routeAndSectorList'}">
                                                    <div class="card-counter sectors">
                                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['sectors_count']}}</span>
                                                        <span class="count-name">Sectors</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{name: 'routeAndSectorList'}">
                                                    <div class="card-counter sectors">
                                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['routes_count']}}</span>
                                                        <span class="count-name">Routes</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{name: 'routeAndSectorList'}">
                                                    <div class="card-counter sectors">
                                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['mtp_count']}}</span>
                                                        <span class="count-name">Multy-pitch</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{name: 'routeAndSectorList'}">
                                                    <div class="card-counter sectors">
                                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['mtp_pitch_count']}}</span>
                                                        <span class="count-name">Multy-pitch pitchs</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-4">
                                                <router-link :to="{name: 'routeAndSectorList'}">
                                                    <div class="card-counter sectors">
                                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['sport_climbing_routes_count']}}</span>
                                                        <span class="count-name">Sport climbing routes</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-4">
                                                <router-link :to="{name: 'routeAndSectorList'}">
                                                    <div class="card-counter sectors">
                                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['top_rope_routes_count']}}</span>
                                                        <span class="count-name">Top rope routes</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-4">
                                                <router-link :to="{name: 'routeAndSectorList'}">
                                                    <div class="card-counter sectors">
                                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['bouldering_routes_count']}}</span>
                                                        <span class="count-name">Boulder</span>
                                                    </div>
                                                </router-link>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <router-link :to="{name: 'routeAndSectorList'}">
                                                    <div class="card-counter sectors">
                                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['tred_routes_count']}}</span>
                                                        <span class="count-name">Tred climbing</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{name: 'routeAndSectorList'}">
                                                    <div class="card-counter sectors">
                                                        <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['aid_routes_count']}}</span>
                                                        <span class="count-name">Aid</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h2>Gallery & images</h2>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <router-link :to="{name: 'gallery'}">
                                                    <div class="card-counter success">
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['gallery_images']}}</span>
                                                        <span class="count-name">Gellry images</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-3">
                                                <router-link :to="{name: 'gallery'}">
                                                    <div class="card-counter success">
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['index_header_images']}}</span>
                                                        <span class="count-name">Index header slider images</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-3">
                                                <router-link :to="{name: 'gallery'}">
                                                    <div class="card-counter success">
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">XXX</span>
                                                        <span class="count-name">Index gallery</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-3">
                                                <router-link :to="{name: 'gallery'}">
                                                    <div class="card-counter success">
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['article_gallery_images']}}</span>
                                                        <span class="count-name">Article gallery images</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h2>Shop / Products</h2>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                        
                                            <div class="col-md-6">
                                                <router-link :to="{name: 'productsList'}">
                                                    <div class="card-counter light">
                                                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['products']}}</span>
                                                        <span class="count-name">Products</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{name: 'productsList'}">
                                                    <div class="card-counter light">
                                                        <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['product_categories']}}</span>
                                                        <span class="count-name">Product categories</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr>
                            
                                <h2>Conflicts</h2>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                        
                                            <div class="col-md-6">
                                                <router-link :to="{name: 'comentsList'}">
                                                    <div class="card-counter danger">
                                                        <i class="fa fa-gavel" aria-hidden="true"></i>
                                                        <span class="count-numbers">XXX</span>
                                                        <span class="count-name">Active competitions</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{name: 'comentsList'}">
                                                    <div class="card-counter danger">
                                                        <i class="fa fa-gavel" aria-hidden="true"></i>
                                                        <span class="count-numbers">XXX</span>
                                                        <span class="count-name">Completed competitions</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h2>Comments</h2>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                        
                                            <div class="col-md-6">
                                                <router-link :to="{name: 'comentsList'}">
                                                    <div class="card-counter dark">
                                                        <i class="fa fa-comment-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">XXX</span>
                                                        <span class="count-name">Article comments</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                            <div class="col-md-6">
                                                <router-link :to="{name: 'comentsList'}">
                                                    <div class="card-counter dark">
                                                        <i class="fa fa-comment-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">XXX</span>
                                                        <span class="count-name">Product comments</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <h2>Users</h2>
                                
                                <hr>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <router-link :to="{name: 'usersList'}">
                                                    <div class="card-counter success">
                                                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                                        <span class="count-numbers">{{this.counts['users']}}</span>
                                                        <span class="count-name">users</span>
                                                    </div>
                                                </router-link>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 4">
                            <div class="col-md-12" >
                                <div class="card">
                                    <div class="card-body" @click="open_notifay_modal()">
                                        Make mail notification
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                <stack-modal
                    :show="is_notifay_modal"
                    title="Send mail notification"
                    @close="is_notifay_modal=false"
                    :saveButton="{ visible: true, title: 'Sand', btnClass: { 'btn btn-primary': true } }"
                    :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
                >
                    <pre class="language-vue">
                        <form ref="editingForm">
                            <div class="container">

                                <div class="form-group clearfix row">
                                    <div class="col-md-12 image_add_modal_form">
                                        <select class="form-control" name="published" id="published" v-model="sending_type">
                                            <option value="1" disabled>Select sending type</option> 
                                            <option value="one_user">Sand to one user</option> 
                                            <option value="one_follower">Sand to one followers</option>
                                            <option value="all_users">Sand to all users</option> 
                                            <option value="all_followers">Sand to all folowers</option> 
                                            <option value="all_users_all_followers">Sand to all followers and all users</option> 
                                        </select> 
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <div class="col-md-12 image_add_modal_form">
                                        <select class="form-control" name="filter" v-model="notification_type">
                                            <option value="1" disabled>Select notification type</option> 
                                            <option value="article">Article notification</option> 
                                            <option value="text">New mail</option> 
                                            <option value="article_and_text">Article and alse new text</option> 
                                        </select> 
                                    </div>
                                </div>

                            </div>
                        </form>
                    </pre>
                    <div slot="modal-footer">
                        <div class="modal-footer">
                            <button
                                type="button"
                                :class="{'btn btn-primary': true}"
                                @click="send_mail()"
                            >
                            Send
                            </button>
                        </div>
                    </div>
                </stack-modal>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
    // import Editor from '../items/canvas/EditorComponent.vue'
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import breadcrumb from '../items/BreadcrumbComponent.vue'

    import { GChart } from 'vue-google-charts'

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
            breadcrumb,
            GChart
        },
        // components: {
        //     Editor,
        // },
        data(){
            return{
                counts: null,
                tab_num: 1,
                is_notifay_modal: false,

                sending_type: '1',
                notification_type: '1',

                week_orders_data: [
                    ['Day', 'Orders', 'Last week'],
                    [13, 37, 80, ],
                    [14, 30, 9, ],
                    [15, 4, 8, ],
                    [16, 7, 40,],
                    [17, 37, 10,],
                    [18, 57, 8, ],
                    [19, 70, 84,],
                ],
                week_orders_options: {
                    title: 'Week Orders',
                    hAxis: {title: 'Day'},
                    vAxis: {title: 'Orders'},
                    legend: 'none',
                },

                year_orders_data: [
                    ['Month', 'Orders', 'Last yare'],
                    ['January',7, 5],
                    ['February',8, 5],
                    ['March',8, 5],
                    ['April',9, 5],
                    ['May',9, 5],
                    ['June',28, 5],
                    ['July',9, 5],
                    ['August',9, 5],
                    ['September',28, 5],
                    ['October',9, 5],
                    ['November',28, 5],
                    ['December',9, 5],
                ],
                year_orders_options: {
                    title: 'Year Orders',
                    hAxis: {title: 'Month'},
                    vAxis: {title: 'Orders'},
                    legend: 'none',
                },
            }
        },
        mounted(){
            this.get_site_counts()
        },
        methods: {

            get_site_counts(){
                axios
                .get('../api/site_data_counts')
                .then(response => {
                    this.counts = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            open_notifay_modal(){
                this.is_notifay_modal = true
            },

            send_mail(){
                alert('send')
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






.card-counter{
    box-shadow: 2px 2px 10px #DADADA;
    margin: 5px;
    padding: 20px 10px;
    background-color: #fff;
    height: 100px;
    border-radius: 5px;
    transition: .3s linear all;
  }

  .card-counter:hover{
    box-shadow: 4px 4px 20px #DADADA;
    transition: .3s linear all;
  }

  .card-counter.primary{
    background-color: #007bff;
    color: #FFF;
  }
  .card-counter.secondary{
    background-color: #6c757d;
    color: #FFF;
  } 
  
  .card-counter.light{
    background-color: #a0a1a1;
    color: #FFF;
  }  
  .card-counter.danger{
    background-color: #ef5350;
    color: #FFF;
  }  

  .card-counter.success{
    background-color: #66bb6a;
    color: #FFF;
  }  
  .card-counter.sectors{
    background-color: #279fbb;
    color: #FFF;
  } 
  .card-counter.warning{
    background-color: #ffc107;
    color: #FFF;
  }  

  .card-counter.info{
    background-color: #26c6da;
    color: #FFF;
  }  

  .card-counter.dark{
    background-color: #343a40;
    color: #FFF;
  } 

  .card-counter i{
    font-size: 5em;
    opacity: 0.2;
  }

  .card-counter .count-numbers{
    position: absolute;
    right: 35px;
    top: 20px;
    font-size: 32px;
    display: block;
  }

  .card-counter .count-name{
    position: absolute;
    right: 35px;
    top: 65px;
    font-style: italic;
    text-transform: capitalize;
    opacity: 0.5;
    display: block;
    font-size: 18px;
  }
</style>