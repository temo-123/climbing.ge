<template>
    <stack-modal
        :show="is_show_mtp_pitch_modal"
        title="MTP Pitches Sequence"
        @close="close_model()"
        :modal-class="{ [MTPPitchModalClass]: true }"
        :saveButton="{ visible: true }"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
        <pre class="language-vue">
            <div class="root">
                <div class="col-md-12">
                    <div class="row">
                        <h2 v-if="mtp_pitchs.length > 0">MTP Pitches</h2>
                        <SlickList lockAxis="y" v-model="mtp_pitchs" v-if="mtp_pitchs.length > 0" tag="table" style="width: 100%">
                            <tr>
                                <td>ID</td>
                                <td>Num</td>
                                <td>Name</td>
                                <td>Grade</td>
                                <td>Height</td>
                                <td>Bolts</td>
                            </tr>
                            <SlickItem v-for="(pitch, index) in mtp_pitchs" :index='index' :key="index" tag="tr">
                                <td>{{ pitch.id }}</td>
                                <td>{{ pitch.num }}</td>
                                <td>{{ pitch.name }}</td>
                                <td v-if="pitch.or_grade != null">{{pitch.grade}} / {{ pitch.or_grade }}</td>
                                <td v-if="pitch.or_grade == null">{{pitch.grade}}</td>
                                <td>{{ pitch.height }}</td>
                                <td>{{ pitch.bolts }}</td>
                            </SlickItem>
                        </SlickList><div v-else class="col-md-12">
                            <p>No pitches found for this MTP.</p>
                        </div>
                    </div>
                </div>
            </div>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="save_pitchs_sequence()"
                    >
                Save sequence
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort';
    import StackModal from '@innologica/vue-stackable-modal';
     
    export default {
        props: [
            'mtp_id_prop'
        ],
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                is_show_mtp_pitch_modal: false,
                
                mtp: [],
                MTPPitchModalClass: 'modal-lg',

                mtp_pitchs: [],
                mtp_id: null,
            }
        },

        mounted() {
            // 
        },

        methods: {
            show_modal(mtp_id){
                this.is_show_mtp_pitch_modal = true;
                this.mtp_id = mtp_id;

                if (this.is_show_mtp_pitch_modal == true) {
                    axios
                    .get('/set_mtp/set_mtp_pitch/get_mtp_pitchs_for_model/' + this.mtp_id)
                    .then(response => {
                        this.mtp_pitchs = response.data;
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
                else{
                    this.mtp_pitchs = [];
                }
            },

            close_model(){
                this.is_show_mtp_pitch_modal = false;
                this.mtp_id = null;
                this.mtp_pitchs = [];
                
                // Emit event to parent to refresh the list
                this.$emit('update');
            },

            save_pitchs_sequence(){
                axios
                .post('/set_mtp/set_mtp_pitch/pitchs_sequence', {
                    pitchs_sequence: this.mtp_pitchs,
                })
                .then((response)=> { 
                    this.close_model();
                })
                .catch(error =>{
                    console.log(error);
                })
            },
        }
    }
</script>

<style>
    .modal-lg {
        width: 80% !important;
        max-width: 1000px;
    }
</style>
