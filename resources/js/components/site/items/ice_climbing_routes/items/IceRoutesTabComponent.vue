<template>
    <tr>
        <td>{{ ice_route.num }}</td>
        <td>{{ ice_route.name }}</td>

        <td v-if="ice_route.height">{{ ice_route.height }}</td>
        <td v-else>?</td>

        <td v-if="ice_route.category == 'dry'">
            <span v-if="ice_route.bolts">{{ ice_route.bolts }}</span>
            <span v-else>?</span>
        </td>
        <td v-else-if="ice_route.category == 'ice'">{{ $t("guide.route.screws") }}</td>

        <td>{{ ice_route.grade }}</td>
        
        <td @click="show_route_modal(ice_route.id)">
            <a style="margin-top: -5%; font-size: 120%"
                ><i class="fa fa-info" aria-hidden="true"></i
            ></a>
        </td>

        <iceRouteModal
            ref="ice_sector_modal"
        />
    </tr>
</template>

<script>
import iceRouteModal from "./modals/IceRouteModalComponent.vue";
export default {
    components: {
        iceRouteModal
    },
    props: [
        'ice_route_prop',
    ],
    data: function () {
        return {
            ice_route: this.ice_route_prop
        };
    },
    mounted() {
        //
    },

    watch: {
        $route(to, from) {
            //
        },
    },
    methods: {

        show_route_modal(ice_route_id){
            this.$refs.ice_sector_modal.show_ice_route_modal(ice_route_id)
        },
    },
};
</script>
