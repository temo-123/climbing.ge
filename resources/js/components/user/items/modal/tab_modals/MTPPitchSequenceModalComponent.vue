<template>
    <StackModal
        v-model="is_show_mtp_pitch_modal"
        :title="$t('admin.users.mtp_pitches_sequence_title')"
        @close="close_model()"
        @save="save_pitchs_sequence"
        :modal-class="{ [MTPPitchModalClass]: true }"
        :saveButton="{ visible: true, title: $t('admin.users.save_sequence_btn') }"
        :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-primary': true } }">
        <template #default>
            <div>
                <div class="root">
                    <div class="col-md-12">
                        <div class="row">
                            <h2 v-if="mtp_pitchs.length > 0">{{ $t('admin.users.mtp_pitches_title') }}</h2>
                            <table v-if="mtp_pitchs.length > 0" style="width: 100%">
                                <thead>
                                    <tr>
                                        <td></td>
                                        <td>{{ $t('common.id') }}</td>
                                        <td>{{ $t('admin.users.col_num') }}</td>
                                        <td>{{ $t('common.name') }}</td>
                                        <td>{{ $t('common.grade') }}</td>
                                        <td>{{ $t('common.height') }}</td>
                                        <td>{{ $t('admin.common.bolts') }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(pitch, index) in mtp_pitchs"
                                        :key="pitch.id || index"
                                        draggable="true"
                                        :class="{ 'drag-over': dragOver === index }"
                                        @dragstart="dragSrc = index"
                                        @dragover.prevent="dragOver = index"
                                        @dragleave="dragOver = null"
                                        @drop.prevent="onDrop(index)"
                                        @dragend="dragOver = null"
                                        style="cursor: grab;"
                                    >
                                        <td><i class="fa fa-bars"></i></td>
                                        <td>{{ pitch.id }}</td>
                                        <td>{{ pitch.num }}</td>
                                        <td>{{ pitch.name }}</td>
                                        <td>{{ pitch.grade }}{{ pitch.or_grade ? ' / ' + pitch.or_grade : '' }}</td>
                                        <td>{{ pitch.height }}</td>
                                        <td>{{ pitch.bolts }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else class="col-md-12">
                                <p>{{ $t('admin.users.no_pitches_found') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </StackModal>
</template>

<script>
    export default {
        props: [
            'mtp_id_prop'
        ],
        data(){
            return {
                is_show_mtp_pitch_modal: false,

                mtp: [],
                MTPPitchModalClass: 'modal-lg',

                mtp_pitchs: [],
                mtp_id: null,
                dragSrc: null,
                dragOver: null,
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

            onDrop(toIndex) {
                if (this.dragSrc === null || this.dragSrc === toIndex) return
                const moved = this.mtp_pitchs.splice(this.dragSrc, 1)[0]
                this.mtp_pitchs.splice(toIndex, 0, moved)
                this.dragSrc = null
                this.dragOver = null
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
    .drag-over {
        background: #d0e8ff;
        outline: 2px dashed #0d6efd;
    }
</style>
