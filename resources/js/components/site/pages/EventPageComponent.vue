<template>
    <div class="container">
        <div class='row'>
            <div class="col-md-6">
                <!-- @component('site.components.breadcrumb')
                    @slot('parent') Home @endslot
                    @slot('link') {{route('index')}} @endslot
                    @slot('active') article @endslot
                    @slot('article') {{ event[0].title }} @endslot
                @endcomponent -->
                <p class="calendar">
                    1<em>mar</em>
                </p>
                
                <h1>{{ event[0].title }} <span @click="add_to_interestid_event(event.id)"> <i class="fa fa-heart-o favorite_icon add_to_favorite" ></i> </span></h1>
                                            

                <span v-html="event[0].text" v-if="this.event[0].text != null"></span>

            </div>
            
            <div class="col-md-6">

                <div class="row" v-if="this.event.map != null">
                    <!-- <h2>Map</h2> -->
                    <h2 id="map">{{ $t('map')}}</h2>
                    <span v-html="event.map"></span>
                </div>

                <!-- {{-- {{ dd(event.id) }} --}} -->
                <!-- @if(Auth::guest()) -->
                <!-- <div style="text-align: center; margin: 4% 0 4% 0;">
                    <a href="#" type="button" class="btn btn-success" style="width: 100%;">Interested</a>
                </div> -->
                <!-- @else
                    @auth
                    @if(isset($interested))
                        @forelse($interested as $iteres)
                            @if(Auth::user().id == $iteres.user_id)
                                <div style="text-align: center; margin: 4% 0 4% 0;">
                                    <a href="route('events_interes', array('events_id'=>event.id, 'actions'=>'del')) }}" type="button" class="btn btn-info" style="width: 100%;">No Interested</a>
                                </div>
                            @else
                                <div style="text-align: center; margin: 4% 0 4% 0;">
                                    <a href="route('events_interes', array('events_id'=>event.id, 'actions'=>'add')) }}" type="button" class="btn btn-success" style="width: 100%;">Interested</a>
                                </div>
                            @endif
                        @empty
                        <div style="text-align: center; margin: 4% 0 4% 0;">
                            <a href="route('events_interes', array('events_id'=>event.id, 'actions'=>'add')) }}" type="button" class="btn btn-success" style="width: 100%;">Interested</a>
                        </div>
                        @endforelse
                    @endif
                    @endauth
                @endif -->
                <!-- <div class="row"> -->
                    <!-- <h2>Contact Information</h2>
                    <span v-html="event[0].info"></span> -->

                    <div class="row" v-if="this.event[0].info || this.event.global_info.info_block != []">
                        <h2 id="how_to_get_there">{{ $t('info')}}</h2>
                        <span v-if="this.event.global_info.info_block.length == 0">
                            <span v-html="this.event[0].info"></span>
                        </span>
                        <span v-else>
                            <span v-if="this.event.global_info.info_block.block_action == 'befor'">
                                <span v-html="this.event.global_info.info_block.text"></span>
                                <span v-html="this.event[0].info"></span>
                            </span>
                            <span v-if="this.event.global_info.info_block.block_action == 'after'">
                                <span v-html="this.event[0].info"></span>
                                <span v-html="this.event.global_info.info_block.text"></span>
                            </span>
                            <span v-if="this.event.global_info.info_block.block_action == 'instead'">
                                <span v-html="this.event.global_info.info_block.text"></span>
                            </span>
                        </span>
                    </div>
                <!-- </div> -->

                <div class="col-md-12"> 
                    <ul class="social-network social-circle" style="text-align: center;">
                           
                        <li v-if="event.fb_link">
                            <a target="_blank" :href="event.fb_link" class="icoFacebook ico_color" title="Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        
                        <li v-if="event.twit_link">
                            <a target="_blank" :href="event.twit_link" class="icoTwitter ico_color" title="Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        
                        <li v-if="event.google_link">
                            <a target="_blank" :href="event.google_link" class="icoGoogle ico_color" title="Google +">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        </li>
                        
                        <li v-if="event.inst_link">
                            <a target="_blank" :href="event.inst_link" class="icoLinkedin ico_color" title="Instagram">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12"> 
                <div class="row">
                    <div style="text-align: center; margin: 4% 30%;">
                        <a @click="add_to_interestid_event(event.id)" type="button" class="btn btn-success" style="width: 100%;">{{ $t('interested event') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <commentForm :article_id="event.id" />
        </div>

        
        <metaData 
            :title = "event[0].title"
            :description = "event[0].description"
            :image = "'../../../../public/images/event_img/'+event.image"
        />

    </div>
</template>

<script>
    import commentForm from '../items/CommentFormComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        props: [
            // 'article',
        ],
        data: function () {
            return {
                event: []
            };
        },
        components: {
            metaData,
            commentForm,
        },
        mounted() {
            this.get_event()
        },
        watch: {
            '$route' (to, from) {
                this.get_event(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_event(){
                axios
                .get('../../api/article/event/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.event = response.data
                })
                .catch(error =>{
                })
            },

            add_to_interestid_event(article_id){
                axios
                .post('../../api/articles/add_to_interested_events/', {
                    event_id: article_id,
                })
                .then(response => {
                    alert(response.data)
                })
                .catch(error =>{
                })
            }
        }
    }
</script>

<style scoped>
    .add_to_favorite{
        float: right; 
        cursor: pointer;
    }
</style>