<template>
    <div class="col-md-12" >

        <h2>Articles</h2>

        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 offset-md-3">
                        <div class="card-counter primary">
                            <i class="fa fa-window-maximize" aria-hidden="true"></i>
                            <span class="count-numbers">{{this.counts['global_articles_count']}}</span>
                            <span class="count-name">Articles</span>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="card-counter primary">
                            <i class="fa fa-language" aria-hidden="true"></i>
                            <span class="count-numbers">{{this.counts['us_articles_count']}}</span>
                            <span class="count-name">English articles</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card-counter primary">
                            <i class="fa fa-language" aria-hidden="true"></i>
                            <span class="count-numbers">{{this.counts['ru_articles_count']}}</span>
                            <span class="count-name">Russion articles</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card-counter primary">
                            <i class="fa fa-language" aria-hidden="true"></i>
                            <span class="count-numbers">{{this.counts['ka_articles_count']}}</span>
                            <span class="count-name">Georgian articles</span>
                        </div>
                    </div>

                </div>

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

                    <div class="col-md-6">
                        <router-link :to="{ name: 'articlelist', params: { article_category: 'news' } }">
                            <div class="card-counter primary">
                                <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                                <span class="count-numbers">{{this.counts['news']}}</span>
                                <span class="count-name">News</span>
                            </div>
                        </router-link>
                    </div>

                    <div class="col-md-6">
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

                    <div class="col-md-6">
                        <router-link :to="{name: 'gallery'}">
                            <div class="card-counter success">
                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                <span class="count-numbers">{{this.counts['gallery_images']}}</span>
                                <span class="count-name">Gellry images</span>
                            </div>
                        </router-link>
                    </div>

                    <div class="col-md-6">
                        <router-link :to="{name: 'gallery'}">
                            <div class="card-counter success">
                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                <span class="count-numbers">{{this.counts['index_header_images']}}</span>
                                <span class="count-name">Index header slider images</span>
                            </div>
                        </router-link>
                    </div>

                </div>
                <div class="row">

                    <div class="col-md-6">
                        <router-link :to="{name: 'gallery'}">
                            <div class="card-counter success">
                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                <span class="count-numbers">XXX</span>
                                <span class="count-name">Index gallery</span>
                            </div>
                        </router-link>
                    </div>

                    <div class="col-md-6">
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

        <hr>

        <h2>Users</h2>

        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 offset-md-3">
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

        <h2>Service followers</h2> 

        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <router-link :to="{name: 'usersList'}">
                            <div class="card-counter success">
                                <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                <span class="count-numbers">XXX</span>
                                <span class="count-name">Guid folowers</span>
                            </div>
                        </router-link>
                    </div>

                    <div class="col-md-6">
                        <router-link :to="{name: 'usersList'}">
                            <div class="card-counter success">
                                <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                <span class="count-numbers">XXX</span>
                                <span class="count-name">Shop folowers</span>
                            </div>
                        </router-link>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            counts: []
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
    }
}
</script>

<style>
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