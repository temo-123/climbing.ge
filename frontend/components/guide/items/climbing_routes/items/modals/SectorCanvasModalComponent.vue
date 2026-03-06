<template>
    <div v-if="showModal" class="modal fade sector-canvas-modal show" id="sectorCanvasModal" tabindex="-1" role="dialog" aria-labelledby="sectorCanvasModalLabel" aria-hidden="true" style="display: block;">
        <div class="modal-backdrop fade show" @click="closeModal"></div>
        <div class="modal-dialog modal-xxl" role="document">
            <div class="modal-content transparent-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="sectorCanvasModalLabel">{{ $t("guide.article.title.sector name") }} - {{ sector.sector.name }}</h5>
                    <button type="button" class="close" @click="closeModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <route_json
                                v-if="sector.sector_imgs.length > 0"
                                :sector_image_id="sector.sector_imgs[0].id"
                                :image_src="'/public/images/sector_img/' + sector.sector_imgs[0].image"
                            />
                        </div>
                        <div class="col-md-6">
                            <h6>{{ $t("guide.route.routes") }}</h6>
                            <div class="routes-list">
                                <table class="table col-md-12 table table-hover">
                                    <tbody>
                                        <tr>
                                            <td>N</td>
                                            <td>{{ $t("guide.route.name") }}</td>
                                            <td>{{ $t("guide.route.height") }}</td>
                                            <td>{{ $t("guide.route.bolts") }}</td>
                                            <td>{{ $t("guide.route.grade fr") }}</td>
                                            <td class="display-none-720px" v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'">{{ $t("guide.route.grade uiaa") }}</td>
                                            <td class="display-none-720px" v-if="activ_grade == 'YDS' || activ_grade == 'yds'">{{ $t("guide.route.grade yds") }}</td>
                                            <td>Info</td>
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr v-for="route in sector.sport_routes" :key="route.id">
                                            <td>{{ route.num }}</td>
                                            <td @click="show_route_modal(route.id)">{{ route.name }}</td>
                                            <td>{{ route.height }}</td>
                                            <td v-if="route.category == 'tred'">Tred</td>
                                            <td v-else-if="route.category == 'top'">Top Rope</td>
                                            <td v-else-if="route.category == 'sport climbing'">
                                                <span v-if="route.bolts">
                                                    {{ route.bolts }}
                                                </span>
                                                <span v-else>
                                                    ?
                                                </span>
                                            </td>
                                            <td v-else >?</td>
                                            <td v-if="route.or_grade != null">
                                                {{ route.grade }} / {{ route.or_grade }}
                                            </td>
                                            <td v-else>{{ route.grade }}</td>
                                            <td class="display-none-720px" v-if="route.or_grade != null">
                                                {{ lead_grade_chart(route.grade) }} /
                                                {{ lead_grade_chart(route.or_grade) }}
                                            </td>
                                            <td class="display-none-720px" v-else>{{ lead_grade_chart(route.grade) }}</td>
                                            <td @click="show_route_modal(route.id)">
                                                <a style="margin-top: -5%; font-size: 120%"
                                                    ><i class="fa fa-info" aria-hidden="true"></i
                                                ></a>
                                            </td>
                                        </tr>
                                        <tr v-for="route in sector.boulder_route" :key="route.id">
                                            <td>{{ route.num }}</td>
                                            <td @click="show_route_modal(route.id)">
                                                {{ route.name }}
                                            </td>
                                            <td>{{ route.height }}</td>
                                            <td></td>
                                            <td v-if="route.or_grade != null">
                                                {{ boulder_grade_chart(route.grade) }} /
                                                {{ boulder_grade_chart(route.or_grade) }}
                                            </td>
                                            <td v-else>
                                                {{ boulder_grade_chart(route.grade) }}
                                            </td>
                                            <td class="display-none-720px" v-if="route.or_grade != null">
                                                {{ route.grade }} / {{ route.or_grade }}
                                            </td>
                                            <td class="display-none-720px" v-else>{{ route.grade }}</td>
                                            <td @click="show_route_modal(route.id)">
                                                <a style="margin-top: -5%; font-size: 120%"
                                                    ><i class="fa fa-info" aria-hidden="true"></i
                                                ></a>
                                            </td>
                                        </tr>
                                        <tr v-for="mtp in sector.mtps" :key="mtp.id">
                                            <td colspan="8">
                                                <strong>MTP: {{ mtp.mtp_name }}</strong>
                                                <table class="table col-md-12 table table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <td>N</td>
                                                            <td>{{ $t("guide.route.name") }}</td>
                                                            <td>{{ $t("guide.route.height") }}</td>
                                                            <td>{{ $t("guide.route.bolts") }}</td>
                                                            <td>{{ $t("guide.route.grade fr") }}</td>
                                                            <td class="display-none-720px" v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'">{{ $t("guide.route.grade uiaa") }}</td>
                                                            <td class="display-none-720px" v-if="activ_grade == 'YDS' || activ_grade == 'yds'">{{ $t("guide.route.grade yds") }}</td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr v-for="pitch in mtp.mtp_pitchs" :key="pitch.pitch_id">
                                                            <td>{{ pitch.num }}</td>
                                                            <td>{{ pitch.name }}</td>
                                                            <td>{{ pitch.height }}</td>
                                                            <td v-if="pitch.category == 'tred'">Tred</td>
                                                            <td v-else-if="pitch.category == 'sport climbing'">
                                                                <span v-if="pitch.bolts">
                                                                    {{ pitch.bolts }}
                                                                </span>
                                                                <span v-else>
                                                                    ?
                                                                </span>
                                                            </td>
                                                            <td v-else>?</td>
                                                            <td v-if="pitch.or_grade != null">
                                                                {{ pitch.grade }} /
                                                                {{ pitch.or_grade }}
                                                            </td>
                                                            <td v-else>{{ pitch.grade }}</td>
                                                            <td class="display-none-720px" v-if="pitch.or_grade != null">
                                                                {{ lead_grade_chart(pitch.grade) }} /
                                                                {{ lead_grade_chart(pitch.or_grade) }}
                                                            </td>
                                                            <td class="display-none-720px" v-else>
                                                                {{ lead_grade_chart(pitch.grade) }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">{{ $t("common.close") }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import route_json from '../canvas/RouteLinesComponent.vue'
import grade_chart from '../../../../../../mixins/grade_chart_mixin.js'

export default {
    mixins: [
        grade_chart,
    ],
    components: {
        route_json
    },
    props: [
        "sector",
    ],
    data() {
        return {
            showModal: false,
        };
    },
    computed: {
        activ_grade() {
            return localStorage.getItem('grade') || 'yds';
        },
    },
    methods: {
        lead_grade_chart(grade_fr) {
            return this.lead(grade_fr)
        },

        boulder_grade_chart(grade_fr) {
            return this.boulder(grade_fr)
        },

        show_modal(sector) {
            this.sector = sector;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
        show_route_modal(route_id) {
            this.$emit('show_route_modal', route_id);
        }
    }
}
</script>

<style scoped>
.sector-canvas-modal .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

/* .transparent-modal {
    background-color: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.modal-body .row {
    align-items: flex-start;
}

.routes-list {
    max-height: 70vh;
    overflow-y: auto;
    border-radius: 8px;
    background-color: rgba(255, 255, 255, 0.9);
    padding: 1rem;
}

.table {
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 8px;
}

.table th, .table td {
    border: 1px solid rgba(0, 0, 0, 0.1);
    padding: 0.75rem;
    font-size: 0.9rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transition: background-color 0.3s ease;
}

.table th {
    background-color: rgba(0, 123, 255, 0.1);
    font-weight: bold;
}

.modal-header {
    background-color: rgba(255, 255, 255, 0.9);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.modal-footer {
    background-color: rgba(255, 255, 255, 0.9);
    border-top: 1px solid rgba(0, 0, 0, 0.1);
} */
@media screen and (min-width: 768px) {
    .modal-xxl {
        width: 100% !important;
    }
}
</style>
