<template>
    <div>
        <h1 class='index_h2'>{{ $t('guide.title.events')}}</h1>

        <h2 class="article_list_short_descriptio">
            {{this.$siteData.event_description}}
        </h2>

        <div class="">
            <div class="row">
                <div class="container articles_filter_bar">
                    <div class="col-md-6 col-sm-6">
                        Select region and filtred spots by region
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control" v-model="event_filtrs" @click="sortByTime()">
                            <option value="next">In future</option>
                            <option value="now">At the moment</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bar"><i class="fa fa-calendar"></i></div>

        <ul class="timeline" v-if="filtred_events.length">
            <eventListCard 
                v-for="event in filtred_events"
                :key='event.id'
                :event="event"
            />
        </ul>

        <div v-else>
          <emptyPageComponent />
        </div>
        
        <metaData 
            :title = "$t('guide.meta.events')"
            :description = "this.$siteData.event_description"
            :image = "'../../../../public/images/meta_img/competition.jpg'"
        />
    </div>
</template>

<script>
// template -> https://codepen.io/krishnab/pen/OPwqbW
// more template -> https://niemvuilaptrinh.medium.com/27-html-timeline-for-web-design-979b8e5d1c05

import metaData from '../items/MetaDataComponent'

import emptyPageComponent from '../../global_components/EmptyPageComponent'

import eventListCard from '../items/cards/EventListCardComponent'

import moment from "moment"; // https://www.npmjs.com/package/vue-moment
    export default {
        components: {
            moment,
        metaData,
        eventListCard,
        emptyPageComponent,
    },
    data(){
        return {
            events: [],
            filtred_events: [],
            event_filtrs: 'next'
        }
    },
    mounted() {
        this.get_event()
    },
    watch: {
        '$route' (to, from) {
            this.get_event()
        }
    },
    methods: {
        get_event(){
            axios
            .get('../../api/event/get_event_on_site_list/'+localStorage.getItem('lang'))
            .then(response => {
                this.events = response.data

                this.sortByTime()
            })
            .catch(error =>{
            })
        },

        sortByTime(){
                let vm = this;
                if (vm.event_filtrs == 'all') {  
                  this.filtred_events = this.events
                }
                else if (vm.event_filtrs == 'next') {  
                  this.filtred_events = []
                    this.filtred_events = this.events.filter(function (item){
                        return (
                            (
                              moment(item.global_event.start_data).format("D") > new Date().getDate() &&
                              moment(item.global_event.end_data).format("M")-1 > new Date().getMonth()
                            )
                          ||
                            (
                              moment(item.global_event.start_data).format("D") == new Date().getDate() &&
                              moment(item.global_event.end_data).format("M")-1 == new Date().getMonth()
                            )
                        )
                    })
                }
                else if (vm.event_filtrs == 'now') {  
                  this.filtred_events = []
                    this.filtred_events = this.events.filter(function (item){
                        return   moment(item.global_event.start_data).format("D") == new Date().getDate() 
                            &&   moment(item.global_event.end_data).format("M")-1 == new Date().getMonth()
                    })
                }
            },
    }
}
</script>

<style lang="scss">

header h1{
  text-align: center;
  font-weight: bold;
  margin-top: 0;
}
  
 header p{
   text-align: center;
   margin-bottom: 0;
 }

.hexa{
  border: 0px;
  float: left;
  text-align: center;
  height: 35px;
  width: 60px;
  font-size: 22px;
  background: #f0f0f0;
  color: #3c3c3c;
  position: relative;
  margin-top: 15px;
}

.hexa:before{
  content: ""; 
  position: absolute; 
  left: 0; 
  width: 0; 
  height: 0;
  border-bottom: 15px solid #f0f0f0;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  top: -15px;
}

.hexa:after{
  content: ""; 
  position: absolute; 
  left: 0; 
  width: 0; 
  height: 0;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  border-top: 15px solid #f0f0f0;
  bottom: -15px;
}

.timeline {
  position: relative;
  padding: 0;
  width: 100%;
  margin-top: 20px;
  list-style-type: none;
}

.timeline:before {
  position: absolute;
  left: 50%;
  top: 0;
  content: ' ';
  display: block;
  width: 2px;
  height: 100%;
  margin-left: -1px;
  background: rgb(213,213,213);
  background: -moz-linear-gradient(top, rgba(213,213,213,0) 0%, rgb(213,213,213) 8%, rgb(213,213,213) 92%, rgba(213,213,213,0) 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(30,87,153,1)), color-stop(100%,rgba(125,185,232,1)));
  background: -webkit-linear-gradient(top, rgba(213,213,213,0) 0%, rgb(213,213,213) 8%, rgb(213,213,213) 92%, rgba(213,213,213,0) 100%);
  background: -o-linear-gradient(top, rgba(213,213,213,0) 0%, rgb(213,213,213) 8%, rgb(213,213,213) 92%, rgba(213,213,213,0) 100%);
  background: -ms-linear-gradient(top, rgba(213,213,213,0) 0%, rgb(213,213,213) 8%, rgb(213,213,213) 92%, rgba(213,213,213,0) 100%);
  background: linear-gradient(to bottom, rgba(213,213,213,0) 0%, rgb(213,213,213) 8%, rgb(213,213,213) 92%, rgba(213,213,213,0) 100%);
  z-index: 5;
}

.timeline li {
  padding: 2em 0;
}

.timeline .hexa{
  width: 16px;
  height: 10px;
  position: absolute;
  background: #00c4f3;
  z-index: 5;
  left: 0;
  right: 0;
  margin-left:auto;
  margin-right:auto;
  top: -30px;
  margin-top: 0;
}

.timeline .hexa:before {
  border-bottom: 4px solid #00c4f3;
  border-left-width: 8px;
  border-right-width: 8px;
  top: -4px;
}

.timeline .hexa:after {
  border-left-width: 8px;
  border-right-width: 8px;
  border-top: 4px solid #00c4f3;
  bottom: -4px;
}

.direction-l,
.direction-r {
  float: none;
  width: 100%;
  text-align: center;
}

.flag-wrapper {
  text-align: center;
  position: relative;
}

.flag {
  position: relative;
  display: inline;
  background: rgb(255,255,255);
  font-weight: 600;
  z-index: 15;
  padding: 6px 10px;
  text-align: left;
  border-radius: 5px;
}

.direction-l .flag:after,
.direction-r .flag:after {
  content: "";
  position: absolute;
  left: 50%;
  top: -15px;
  height: 0;
  width: 0;
  margin-left: -8px;
  border: solid transparent;
  border-bottom-color: rgb(255,255,255);
  border-width: 8px;
  pointer-events: none;
}

.direction-l .flag {
  -webkit-box-shadow: -1px 1px 1px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.15);
  -moz-box-shadow: -1px 1px 1px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.15);
  box-shadow: -1px 1px 1px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.15);
}

.direction-r .flag {
  -webkit-box-shadow: 1px 1px 1px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.15);
  -moz-box-shadow: 1px 1px 1px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.15);
  box-shadow: 1px 1px 1px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.15);
}

.time-wrapper {
  display: block;
  position: relative;
  margin: 4px 0 0 0;
  z-index: 14;
  line-height: 1em;
//   vertical-align: middle;
  color: #fff;
}

.direction-l .time-wrapper {
  float: none;
}

.direction-r .time-wrapper {
  float: none;
}

.time {
  background: #00c4f3;
  display: inline-block;
  padding: 8px;
}

.desc {
  position: relative;
  margin: 1em 0 0 0;
  padding: 1em;
  // background: rgb(254,254,254);
  background: rgb(247 252 254);
  -webkit-box-shadow: 0 0 1px rgba(0,0,0,0.20);
  -moz-box-shadow: 0 0 1px rgba(0,0,0,0.20);
  // box-shadow: 0 0 1px rgba(0,0,0,0.20);
  box-shadow: 0 0 1px rgb(0 0 0 / 76%);
  z-index: 15;
}

.event_timeline_title{
    margin-top: 4px !important;
    margin-bottom: 5px !important;
    color: #333 !important;
}

.direction-l .desc,
.direction-r .desc {
  position: relative;
  margin: 1em 1em 0 1em;
  padding: 1em;
  z-index: 15;
}

@media(min-width: 768px){
  .timeline {
    width: 660px;
    margin: 0 auto;
    margin-top: 20px;
  }

  .timeline li:after {
    content: "";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
  }
  
  .timeline .hexa {
    left: -28px;
    right: auto;
    top: 8px;
  }

  .timeline .direction-l .hexa {
    left: auto;
    right: -28px;
  }

  .direction-l {
    position: relative;
    width: 310px;
    float: left;
    text-align: right;
  }

  .direction-r {
    position: relative;
    width: 310px;
    float: right;
    text-align: left;
  }

  .flag-wrapper {
    display: inline-block;
  }
  
  .flag {
    font-size: 18px;
  }

  .direction-l .flag:after {
    left: auto;
    right: -16px;
    top: 50%;
    margin-top: -8px;
    border: solid transparent;
    border-left-color: rgb(254,254,254);
    border-width: 8px;
  }

  .direction-r .flag:after {
    top: 50%;
    margin-top: -8px;
    border: solid transparent;
    border-right-color: rgb(254,254,254);
    border-width: 8px;
    left: -8px;
  }

  .time-wrapper {
    display: inline;
    vertical-align: middle;
    margin: 0;
  }

  .direction-l .time-wrapper {
    float: left;
  }

  .direction-r .time-wrapper {
    float: right;
  }

  .time {
    padding: 5px 10px;
  }

  .direction-r .desc {
    margin: 1em 0 0 0.75em;
  }
}

@media(min-width: 992px){
  .timeline {
    width: 800px;
    margin: 0 auto;
    margin-top: 20px;
  }

  .direction-l {
    position: relative;
    width: 380px;
    float: left;
    text-align: right;
  }

  .direction-r {
    position: relative;
    width: 380px;
    float: right;
    text-align: left;
  }
}
</style>