<template>
    <StackModal
            :show="is_show_edit_modal"
            :title="'Edit Pitch'"
            size="xl"
            @close="close_modal()"
            :saveButton="{ visible: false }"
            :cancelButton="{ visible: false }"
        >
        <div>
            <form id="mtp_edit_form" @submit.prevent="save()">
                <select class="form-control" v-model="data.category" required>
                    <option value="" disabled>Please select mtp type</option>
                    <option value="sport climbing">Sport climbing</option>
                    <option value="tred">Tred Climbing</option>
                </select>
                <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.grade" required>
                    <option value=""> No grade </option>
                    <option value="Project">Project</option>
                    <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>
                <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.or_grade">
                    <option value=""> No grade </option>
                    <option value="Project">Project</option>
                    <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>

                <input type="text" name="name" v-model="data.name" class="form-control" placeholder="name">
                <input type="number" name="bolts" v-model="data.bolts" class="form-control" placeholder="Bolts">
                <input type="number" name="height" class="form-control" v-model="data.height" placeholder="Height">
                <input type="text" name="auther" class="form-control" v-model="data.author" placeholder="Bolter">
                <input type="date" name="creation_data" class="form-control" v-model="data.creation_data" placeholder="Bolting Data">
                <input type="text" name="first_ascent" class="form-control" v-model="data.first_ascent" placeholder="First ascent">

                <text_block_localization
                    v-model:en_value="data.text_us"
                    v-model:ka_value="data.text_ka"
                />

                <div class="mt-3">
                    <button type="submit" form="mtp_edit_form" class="btn btn-primary">Save</button>
                </div>
            </form>

            <!-- Canvas drawing editor for this pitch -->
            <CanvasPitchEditor
                v-if="editing_pitch_id"
                ref="pitchEditorRef"
                :pitch_id_prop="editing_pitch_id"
                :sector_id_prop="data.sector_id || mtp_sector_id"
                :pitch_json_prop="data.json ? data.json.json : null"
                :sector_image_id_prop="data.json ? data.json.sector_image_id : null"
                class="mt-4"
            />
        </div>
    </StackModal>
</template>

<script>
    import CanvasPitchEditor from './CanvasPitchEditorComponent.vue'
    import text_block_localization from '../../../../../items/form/parts/TextBlockLocalithationComponent.vue'

    export default {
        components: {
            CanvasPitchEditor,
            text_block_localization,
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
                    author: "",
                    creation_data: "",
                    first_ascent: "",
                    anchor_type: "",
                    category: "",
                    json: null,
                    sector_id: null,
                },

                sport_route_grade: [
                    "4",
                    "5a", "5b", "5c", "5c+",
                    "6a", "6a+", "6b", "6b+", "6c", "6c+",
                    "7a", "7a+", "7b", "7b+", "7c", "7c+",
                    "8a", "8a+", "8b", "8b+", "8c", "8c+",
                    "9a", "9a+", "9b", "9b+", "9c", "9c+",
                ],
                is_show_edit_modal: false,
            }
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
                    author: "",
                    creation_data: "",
                    first_ascent: "",
                    json: null,
                    sector_id: null,
                };
            },
        }
    }
</script>
