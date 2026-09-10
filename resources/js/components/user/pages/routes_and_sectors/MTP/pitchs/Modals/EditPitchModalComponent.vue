<template>
    <StackModal
            :show="is_show_edit_modal"
            :title="$t('admin.routes_sectors.edit_pitch_title')"
            size="xxl"
            @close="close_modal()"
            :saveButton="{ visible: false }"
            :cancelButton="{ visible: false }"
        >
        <div>
            <form id="mtp_edit_form" @submit.prevent="save()">
                <select class="form-control" v-model="data.category" required>
                    <option value="" disabled>{{ $t('admin.routes_sectors.please_select_mtp_type') }}</option>
                    <option value="sport climbing">{{ $t('admin.routes_sectors.sport_climbing') }}</option>
                    <option value="tred">{{ $t('admin.routes_sectors.tred_climbing') }}</option>
                </select>
                <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.grade" required>
                    <option value=""> {{ $t('admin.routes_sectors.no_grade_option') }} </option>
                    <option value="Project">{{ $t('admin.routes_sectors.project_option') }}</option>
                    <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>
                <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.or_grade">
                    <option value=""> {{ $t('admin.routes_sectors.no_grade_option') }} </option>
                    <option value="Project">{{ $t('admin.routes_sectors.project_option') }}</option>
                    <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>

                <input type="text" name="name" v-model="data.name" class="form-control" :placeholder="$t('common.name')">
                <input type="number" name="bolts" v-model="data.bolts" class="form-control" :placeholder="$t('admin.common.bolts')">

                <label class="mt-2 mb-1 d-block">{{ $t('admin.routes_sectors.bolts_type_colon') }}</label>
                <select class="form-control" v-model="data.bolts_type">
                    <option value="">{{ $t('admin.routes_sectors.bolts_type_placeholder') }}</option>
                    <option :value=null>?</option>
                    <option value="glued">{{ $t('admin.routes_sectors.bolts_type_glued') }}</option>
                    <option value="hangerr">{{ $t('admin.routes_sectors.bolts_type_hanger') }}</option>
                </select>

                <input type="number" name="height" class="form-control" v-model="data.height" :placeholder="$t('common.height')">
                <input type="text" name="auther" class="form-control" v-model="data.author" :placeholder="$t('admin.routes_sectors.bolter_placeholder')">

                <label class="mt-2 mb-1 d-block">{{ $t('admin.routes_sectors.bolting_date_placeholder') }}</label>
                <partial_date_input v-model="data.creation_data" />

                <input type="text" name="first_ascent" class="form-control mt-2" v-model="data.first_ascent" :placeholder="$t('admin.routes_sectors.first_ascent_placeholder')">

                <label class="mt-2 mb-1 d-block">{{ $t('admin.routes_sectors.first_ascent_date_placeholder') }}</label>
                <partial_date_input v-model="data.first_ascent_date" />

                <text_block_localization
                    v-model:en_value="data.text_us"
                    v-model:ka_value="data.text_ka"
                />

                <div class="mt-3">
                    <button type="submit" form="mtp_edit_form" class="btn btn-primary">{{ $t('common.save') }}</button>
                </div>
            </form>

            <!-- Canvas drawing editor for this pitch -->
            <CanvasPitchEditor
                v-if="editing_pitch_id"
                ref="pitchEditorRef"
                :pitch_id_prop="editing_pitch_id"
                :sector_id_prop="data.sector_id || mtp_sector_id"
                :pitch_json_prop="data.json ? data.json.json : null"
                :pitch_json_meta_prop="pitchJsonMeta"
                :sector_image_id_prop="data.json ? data.json.sector_image_id : null"
                class="mt-4"
            />
        </div>
    </StackModal>
</template>

<script>
    import CanvasPitchEditor from './CanvasPitchEditorComponent.vue'
    import text_block_localization from '../../../../../items/form/parts/TextBlockLocalithationComponent.vue'
    import partial_date_input from '../../../../../items/form/parts/PartialDateInputComponent.vue'
    import { grade } from '../../../../../../../mixins/grade_mixin.js'

    export default {
        components: {
            CanvasPitchEditor,
            text_block_localization,
            partial_date_input,
        },
        props: {
            mtp_sector_id: { default: null },
        },
        data() {
            return {
                errors: [],
                status: "",
                is_loading: false,
                editing_pitch_id: null,

                data: {
                    mtp_id: "",
                    grade: "",
                    or_grade: "",
                    name: "",
                    text_us: "",
                    text_ka: "",
                    height: "",
                    bolts: "",
                    bolts_type: "",
                    author: "",
                    creation_data: "",
                    first_ascent: "",
                    first_ascent_date: "",
                    anchor_type: "",
                    category: "",
                    json: null,
                    sector_id: null,
                },

                // Same list Route's grading form uses (grade_mixin.js's lead()) —
                // this used to be its own hardcoded copy that had drifted from
                // Route's (e.g. Route was missing "5c+"), so the two forms could
                // silently offer different grades for the same French scale.
                sport_route_grade: grade.lead(),
                is_show_edit_modal: false,
            }
        },

        computed: {
            // The background photo's own position/size within the Paper.js view at
            // save time — needed by the editor to rescale saved strokes onto the
            // current background fit.
            pitchJsonMeta() {
                const j = this.data.json;
                if (!j) return null;
                return {
                    canvas_width: j.canvas_width, canvas_height: j.canvas_height,
                    bg_left: j.bg_left, bg_top: j.bg_top,
                    bg_width: j.bg_width, bg_height: j.bg_height,
                };
            },
        },
        methods: {
            show_modal(id) {
                this.editing_pitch_id = id;
                this.is_show_edit_modal = true;
                this.get_editing_mtp_data(id);
            },
            close_modal() {
                this.is_show_edit_modal = false;
                this.clear_form();
            },

            get_editing_mtp_data(id) {
                this.is_loading = true;
                axios.get("/set_mtp/set_mtp_pitch/get_editin_pitch/" + id)
                    .then(response => { this.data = response.data; })
                    .catch(error => console.log(error))
                    .finally(() => this.is_loading = false);
            },
            save() {
                this.is_loading = true;
                axios.post('/set_mtp/set_mtp_pitch/mtp_pitch_edit/' + this.editing_pitch_id, { data: this.data })
                    .then(() => {
                        this.close_modal();
                        this.$emit('update');
                    })
                    .catch(() => { this.status = "error"; })
                    .finally(() => this.is_loading = false);
            },
            clear_form() {
                this.data = {
                    mtp_id: this.data.mtp_id,
                    category: this.data.category,
                    grade: "",
                    or_grade: "",
                    name: "",
                    text_us: "",
                    text_ka: "",
                    height: "",
                    bolts: "",
                    bolts_type: "",
                    author: "",
                    creation_data: "",
                    first_ascent: "",
                    first_ascent_date: "",
                    json: null,
                    sector_id: null,
                };
            },
        }
    }
</script>
