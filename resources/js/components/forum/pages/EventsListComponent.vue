<template>
    <div>
        <main role="main" class="container">

        <div class="row">
            <div class="container"  v-if="this.events.length > 0">
                <h2 class='index_h2'>{{ $t('title events') }}</h2>

                <div class="bar"><i class="fa fa-calendar"></i></div>
                <h6> <span v-html="this.$siteData.event_description"></span></h6>

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

            <rightMenu />

        </div>
        </main>
    </div>
</template>

<script>
    import rightMenu from '../items/RightMenuComponent.vue'
    import eventCard from '../../site/items/cards/EventCardComponent'

    export default {
        components: {
            rightMenu,
            eventCard
        },
        data(){
            return{
                events: []
            }
        },
        mounted() {
            this.get_event()
        },
        methods: {
            get_event(){
                axios
                .get('../api/event')
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