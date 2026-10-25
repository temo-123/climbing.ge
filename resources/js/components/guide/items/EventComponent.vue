<template>
    <div class="container h-recent-work events" id="event" v-if="this.events.length > 0">
        <div class="row">
            <div class="container">

                <h2 class='index_h2 functional_block_title'>
                    <router-link :to="{name: 'events', params: {locale: this.$i18n.locale === 'ka' ? 'ka' : undefined}}">
                        {{ $t('guide.title.events') }}
                    </router-link>
                </h2>

                <div class="bar"><i class="fa fa-calendar"></i></div>
                <h3 class='article_list_short_description'> {{this.$siteData.data.event_description}}</h3>

                <div class="row g-4 cg-home-events">
                    <div v-for="event in events" :key="event.global_event.id" class="col-md-6 col-lg-4">
                        <eventCard :event="event" />
                    </div>
                </div>
                <p class="text-center mt-4">
                    <router-link class="btn btn-outline-secondary" :to="{name: 'events', params: {locale: this.$i18n.locale === 'ka' ? 'ka' : undefined}}">
                        {{ $t('guide.events.all_events') }} <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </router-link>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import eventCard from './cards/EventCardComponent.vue'
    import { load_interested } from '../../../services/guide/interested.js'
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
            load_interested()
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