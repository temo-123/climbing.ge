<template>
    <div class="h-recent-work services" id="services" v-if="team_members.length > 0">
        <div class="container">

            <h2 class='index_h2'>{{ $t('guide.team_members_title') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
            <h3 class='article_list_short_description'> <span v-html="this.$siteData.data.team_member_description"></span> </h3>

            <team_cards_slider :users="team_members" />

        </div>
    </div>
</template>

<script>
    import team_cards_slider from "../../global_components/TeamCardsSliderComponent.vue";

    export default {
        props: [
            // 'team_members',
        ],
        data: function () {
            return {
                team_members: [],
            };
        },
        components: {
            team_cards_slider
        },
        mounted() {
            this.get_team_members()
        },
        methods: {
            get_team_members(){
                axios
                .get('/get_team/get_team_members/')
                .then(response => {
                    this.team_members = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>
