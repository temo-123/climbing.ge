<template>
    <StackModal
            :show="is_show_add_modal"
            :title="$t('admin.routes_sectors.add_pitch_title')"
            size="xxl"
            @close="close_modal()"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <form id="mtp_add_form" @submit.prevent="save()">
                <select class="form-control"  v-model="data.category" required>
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
            </form>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form='mtp_add_form'
                    :class="{'btn btn-primary': true}"
                >
                    {{ $t('common.save') }}
                </button>
            </div>
        </div>
    </StackModal>
</template>

<script>
    import Editor from '../../../../../items/canvas/EditorComponent.vue'
    import text_block_localization from '../../../../../items/form/parts/TextBlockLocalithationComponent.vue'
    import partial_date_input from '../../../../../items/form/parts/PartialDateInputComponent.vue'
    import { grade } from '../../../../../../../mixins/grade_mixin.js'

    export default {
        components: {
            Editor,
            text_block_localization,
            partial_date_input,
        },
        mixins: [],
        data() {
            return {
                description_editor: {},
                errors: [],
                status: "",
                problem_status: "",

                is_loading: false,
        
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
                },

                go_back_action: false,

                // Same list Route's grading form uses (grade_mixin.js's lead()) —
                // this used to be its own hardcoded copy that had drifted from
                // Route's (e.g. Route was missing "5c+"), so the two forms could
                // silently offer different grades for the same French scale.
                sport_route_grade: grade.lead(),
                is_show_add_modal: false,
            }
        },
    
        mounted() {
            // this.get_region_data()
        },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action_query == true){
                if (window.confirm(this.$t('common.confirm_leave_unsaved'))) {
                    this.is_back_action_query = false;
                    next()
                } else {
                    next(false)
                }
            }else {
                next()
            }
        },
    
        methods: {
            show_modal(id){
                this.data.mtp_id = id
                this.is_show_add_modal = true
            },
            close_modal(){
                this.is_show_add_modal = false

                this.clear_form()
            },

            save: function () {
                this.is_loading = true
                axios
                .post('/set_mtp/set_mtp_pitch/mtp_pitch_add/', {
                    data: this.data,
                })
                .then(response => {
                    this.close_modal()
                    this.$emit('update')
                })
                .catch(error =>{
                    this.status = "error"
                })
                .finally(() => {
                    this.is_loading = false
                })
            },
        
            clear_form(){
                this.data = {
                    mtp_id: this.data.mtp_id,
                    category: "",
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
                };
            },
        }
    }
</script>