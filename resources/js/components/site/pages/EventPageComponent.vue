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
                    {{ event .start_data_day }}<em>{{event.start_data_month}}</em>
                </p>
                
                <h1>{{ event[0].title }}</h1>

                <span v-html="event[0].text"></span>


                <div class="row">
                    <div style="text-align: center; margin: 4% 0 4% 0;">
                        <a href="#" type="button" class="btn btn-success" style="width: 100%;">Interested</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">

                <div class="row">
                    <h2>Map</h2>
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
                <div class="row">
                    <h2>Contact Information</h2>
                    <span v-html="event[0].info"></span>
                </div>

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
            <commentForm :article_id="event.id" />
        </div>
    </div>
</template>

<script>
    import commentForm from '../items/CommentFormComponent'

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
            commentForm,
        },
        mounted() {
            this.get_event()
        },
        methods: {
            get_event(){
                axios
                .get('../api/article/'+this.$route.params.url_title)
                .then(response => {
                    this.event = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>