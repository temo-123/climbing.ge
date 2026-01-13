<template>
    <div class="container h-recent-work events" id="event" v-if="this.events.length > 0">
        <div class="row">
            <div class="container">

                <h2 class='index_h2 functional_block_title'>
                    <router-link :to="{name: 'events', params: {lang: this.$i18n.locale}}">
                        {{ $t('guide.title.events') }}
                    </router-link>
                </h2>

                <div class="bar"><i class="fa fa-calendar"></i></div>
                <h3 class='article_list_short_description'> {{this.$siteData.data.event_description}}</h3>

                <div class="container">
                    <div class="row event-list">
                        <eventCard 
                            v-for="event in events"
                            :key='event.id'
                            :event="event"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import eventCard from './cards/IndexEventCardComponent'
    export default {
        data(){
            return {
                events: [],
            }
        },
        components: {
            eventCard,

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
                .get('/get_event/get_event_on_index_page/'+localStorage.getItem('lang'))
                .then(response => {
                    this.events = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>

<style>

</style>