<template>
<StackModal
    v-model="is_show_modal"
    :title="$t('guide.article.title.sector name') + ' - ' + (sectorData ? sectorData.sector.name : '')" size="full"
    :saveButton="{ visible: false }"
    :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    @close="close_modal"
  >
    <div class="row">
      <div class="col-md-6">
        <route_json
          v-if="sectorData && sectorData.sector_imgs && sectorData.sector_imgs.length > 0"
          :sector_image_id="sectorData.sector_imgs[0].id"
          :image_src="'/public/images/sector_img/' + sectorData.sector_imgs[0].image"
        />
      </div>
      <div class="col-md-6">
        <h6>{{ $t("guide.route.routes") }}</h6>
        <div class="routes-list">
          <table class="table table-hover">
            <thead>
              <tr>
                <td>N</td>
                <td>{{ $t("guide.route.name") }}</td>
                <td>{{ $t("guide.route.height") }}</td>
                <td>{{ $t("guide.route.bolts") }}</td>
                <td>{{ $t("guide.route.grade fr") }}</td>
                <td v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'" class="d-none d-md-table-cell">{{ $t("guide.route.grade uiaa") }}</td>
                <td v-if="activ_grade == 'YDS' || activ_grade == 'yds'" class="d-none d-md-table-cell">{{ $t("guide.route.grade yds") }}</td>
                <td>Info</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(route, index) in (sectorData ? sectorData.sport_routes : [])" :key="route.id || index">
                <td>{{ route.num }}</td>
                <td @click="show_route_modal(route.id)" style="cursor: pointer;">{{ route.name }}</td>
                <td>{{ route.height }}</td>
                <td v-if="route.category == 'tred'">Tred</td>
                <td v-else-if="route.category == 'top'">Top Rope</td>
                <td v-else-if="route.category == 'sport climbing'">
                  <span v-if="route.bolts">{{ route.bolts }}</span>
                  <span v-else>?</span>
                </td>
                <td v-else>?</td>
                <td v-if="route.or_grade != null">{{ route.grade }} / {{ route.or_grade }}</td>
                <td v-else>{{ route.grade }}</td>
                <td v-if="route.or_grade != null" class="d-none d-md-table-cell">{{ lead_grade_chart(route.grade) }} / {{ lead_grade_chart(route.or_grade) }}</td>
                <td v-else class="d-none d-md-table-cell">{{ lead_grade_chart(route.grade) }}</td>
                <td @click="show_route_modal(route.id)" style="cursor: pointer;">
                  <i class="fa fa-info" style="font-size: 120%;"></i>
                </td>
              </tr>
              <tr v-for="(route, index) in (sectorData ? sectorData.boulder_route : [])" :key="'b-' + (route.id || index)">
                <td>{{ route.num }}</td>
                <td @click="show_route_modal(route.id)" style="cursor: pointer;">{{ route.name }}</td>
                <td>{{ route.height }}</td>
                <td></td>
                <td v-if="route.or_grade != null">{{ boulder_grade_chart(route.grade) }} / {{ boulder_grade_chart(route.or_grade) }}</td>
                <td v-else>{{ boulder_grade_chart(route.grade) }}</td>
                <td v-if="route.or_grade != null" class="d-none d-md-table-cell">{{ route.grade }} / {{ route.or_grade }}</td>
                <td v-else class="d-none d-md-table-cell">{{ route.grade }}</td>
                <td @click="show_route_modal(route.id)" style="cursor: pointer;">
                  <i class="fa fa-info" style="font-size: 120%;"></i>
                </td>
              </tr>
              <!-- Add MTP if available -->
              <tr v-for="(mtp, index) in (sectorData ? sectorData.mtps : [])" :key="'m-' + (mtp.id || index)" v-if="sectorData && sectorData.mtps">
                <td>{{ mtp.num || index + 1 }}</td>
                <td @click="show_mtp_modal(mtp.id)" style="cursor: pointer;">{{ mtp.name }}</td>
                <td>{{ mtp.height }}</td>
                <td v-if="mtp.category == 'sport climbing'">{{ mtp.bolts }}</td>
                <td v-else>Tred</td>
                <td>{{ mtp.grade }}</td>
                <td class="d-none d-md-table-cell">{{ lead_grade_chart(mtp.grade) }}</td>
                <td @click="show_mtp_modal(mtp.id)" style="cursor: pointer;">
                  <i class="fa fa-info" style="font-size: 120%;"></i>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </StackModal>
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
            is_show_modal: false,
            sectorData: null,
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
        show_sector_modal(sector) {
            this.sectorData = sector;
            this.is_show_modal = true;
        },
        close_modal() {
            this.is_show_modal = false;
            this.sectorData = null;
        },
        show_route_modal(route_id) {
            this.$emit('show_route_modal', route_id);
            this.close_modal();
        },
        show_mtp_modal(mtp_id) {
            this.$emit('show_mtp_modal', mtp_id);
            this.close_modal();
        }
    }
}
</script>

<style scoped>
.sector-canvas-modal .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}
.routes-list {
    max-height: 70vh;
    overflow-y: auto;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}
@media screen and (min-width: 768px) {
    .modal-xxl {
        width: 100% !important;
    }
}
</style>
