<template>
    <div class="col-md-12">
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <h2>{{ $t('admin.routes_sectors.pitches_title') }}</h2>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <button class="btn btn-primary float-left" @click="add_pitch()">{{ $t('admin.routes_sectors.add_pitch') }}</button>
                    <button class="btn btn-warning float-left ml-2" @click="change_sequence()">{{ $t('admin.routes_sectors.change_sequence') }}</button>
                    <button class="btn btn-success float-right" @click="get_MTP_pitchs()">{{ $t('common.refresh') }}</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th>{{ $t('common.id') }}</th>
                            <th>{{ $t('common.name') }}</th>
                            <th>{{ $t('common.grade') }}</th>
                            <th>{{ $t('common.height') }}</th>
                            <th>{{ $t('admin.common.bolts') }}</th>
                            <th>{{ $t('common.edit') }}</th>
                            <th>{{ $t('common.delete') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="pitch in pitchs" :key="pitch.id">
                            <td>{{ pitch.id }}</td>
                            <td>{{ pitch.name }}</td>
                            <td>{{ pitch.grade }}</td>
                            <td>{{ pitch.height }}</td>
                            <td>{{ pitch.bolts }}</td>
                            <td>
                                <button class="btn btn-warning" @click="edit_pitch(pitch.id)">{{ $t('common.edit') }}</button>
                            </td>
                            <td>
                                <button class="btn btn-danger" @click="del_pitch(pitch.id)">{{ $t('common.delete') }}</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <add_pitch_modal 
            @update="get_MTP_pitchs"
            ref="add_pitch_modal"
        />
        <edit_pitch_modal
            @update="get_MTP_pitchs"
            :mtp_sector_id="sector_id_prop"
            ref="edit_pitch_modal"
        />
        <mtp_pitch_sequence_modal 
            @update="get_MTP_pitchs"
            ref="mtp_pitch_sequence_modal"
        />

    </div>
</template>

<script>
    import add_pitch_modal from './Modals/AddPitchModalComponent.vue'
    import edit_pitch_modal from './Modals/EditPitchModalComponent.vue'
    import mtp_pitch_sequence_modal from "../../../../items/modal/tab_modals/MTPPitchSequenceModalComponent.vue"

    export default {
        props: ['mtp_id_prop', 'sector_id_prop'],
        components: {
            add_pitch_modal,
            edit_pitch_modal,
            mtp_pitch_sequence_modal,
        },
        data(){
            return {
                pitchs: [],
                mtp_id: null,
            }
        },
        watch: {
            mtp_id_prop: function(){
                this.mtp_id = this.mtp_id_prop

                this.get_MTP_pitchs()
            },
        },
        mounted() {
            // 
        },
        methods: {
            get_MTP_pitchs(){
                axios
                .get('/set_mtp/set_mtp_pitch/get_mtp_pitchs_for_model/' + this.mtp_id)
                .then(response => {
                    this.pitchs = response.data
                })
                .catch(error => console.log(error))
            },
            add_pitch(){
                this.$refs.add_pitch_modal.show_modal(this.mtp_id)
            },
            change_sequence(){
                this.$refs.mtp_pitch_sequence_modal.show_modal(this.mtp_id)
            },
            edit_pitch(id){
                this.$refs.edit_pitch_modal.show_modal(id)
            },
            del_pitch(pitch_id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .post('/set_mtp/set_mtp_pitch/del_pitch/'+pitch_id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_MTP_pitchs()
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>