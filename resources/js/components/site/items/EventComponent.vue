<template>
    <div class="container h-recent-work events" id="event" v-if="this.events.length > 0">
        <div class="row">
            <div class="container">

                <h2 class='index_h2'>{{ $t('title events') }}</h2>

                <div class="bar"><i class="fa fa-calendar"></i></div>
                <h3> <span v-html="this.$siteData.event_description"></span></h3>

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
    import eventCard from './cards/EventCardComponent'
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
                .get('../api/articles/event/'+localStorage.getItem('lang'))
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