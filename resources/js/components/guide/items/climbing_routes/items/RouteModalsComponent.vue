<template>
    <!-- The route + multi-pitch modals (with their review / ascent modals), for
         views that list routes outside SectorComponent (sector table view).
         Call open_route(id) / open_mtp(id) through a ref. -->
    <div>
        <route_modal
            ref="open_route_modal"
            @show_route_review_modal="id => $refs.open_route_review_modal.show_route_review_modal(id)"
            @show_route_all_review_modal="id => $refs.show_route_all_review_modal.show_route_all_review_modal(id)"
            @show_route_ascent_modal="(id, route) => $refs.open_route_ascent_modal.show_route_ascent_modal(id, route)"
            @show_route_ascents_modal="id => $refs.show_route_ascents_modal.show_route_ascents_modal(id)"
        />
        <create_route_ascent_modal ref="open_route_ascent_modal" @back_route_modal="open_route" />
        <route_ascents_list_modal ref="show_route_ascents_modal" @back_route_modal="open_route" />
        <create_route_review_modal ref="open_route_review_modal" @back_route_modal="open_route" />
        <route_all_reviews_modal ref="show_route_all_review_modal" @back_route_modal="open_route" />

        <mtp_modal
            ref="open_mtp_modal"
            @show_mtp_review_modal="id => $refs.open_mtp_review_modal.show_mtp_review_modal(id)"
            @show_mtp_all_review_modal="id => $refs.show_mtp_all_review_modal.show_mtp_all_review_modal(id)"
            @show_mtp_ascent_modal="id => $refs.open_mtp_ascent_modal.show_mtp_ascent_modal(id)"
            @show_mtp_ascents_list_modal="id => $refs.show_mtp_ascents_list_modal.show_mtp_ascents_list_modal(id)"
        />
        <create_mtp_ascent_modal ref="open_mtp_ascent_modal" @back_mtp_modal="open_mtp" />
        <mtp_ascents_list_modal ref="show_mtp_ascents_list_modal" @back_mtp_modal="open_mtp" />
        <create_mtp_review_modal ref="open_mtp_review_modal" @back_mtp_modal="open_mtp" />
        <mtp_all_reviews_modal ref="show_mtp_all_review_modal" @back_mtp_modal="open_mtp" />
    </div>
</template>

<script>
import route_modal from "./modals/RouteModalComponent.vue";
import create_route_review_modal from "./modals/feedbacks/CreateRouteReviewModal.vue";
import route_all_reviews_modal from "./modals/feedbacks/RouteAllReviewsModal.vue";
import create_route_ascent_modal from "./modals/ascents/CreateRouteAscentModal.vue";
import route_ascents_list_modal from "./modals/ascents/RouteAscentsListModal.vue";
import mtp_modal from "./modals/MTPModalComponent.vue";
import create_mtp_review_modal from "./modals/feedbacks/CreateMtpReviewModal.vue";
import mtp_all_reviews_modal from "./modals/feedbacks/MtpAllReviewsModal.vue";
import create_mtp_ascent_modal from "./modals/ascents/CreateMtpAscentModal.vue";
import mtp_ascents_list_modal from "./modals/ascents/MtpAscentsListModal.vue";

export default {
    name: 'RouteModalsComponent',
    components: {
        route_modal, create_route_review_modal, route_all_reviews_modal, create_route_ascent_modal, route_ascents_list_modal,
        mtp_modal, create_mtp_review_modal, mtp_all_reviews_modal, create_mtp_ascent_modal, mtp_ascents_list_modal,
    },
    methods: {
        open_route(id) { this.$refs.open_route_modal.show_route_modal(id); },
        open_mtp(id) { this.$refs.open_mtp_modal.show_mtp_modal(id); },
    },
};
</script>
