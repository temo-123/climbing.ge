<template>
    <div class="col-md-12">
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <h2>Pitches</h2>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <button class="btn btn-primary float-left" @click="add_pitch()">Add Pitch</button>
                    <button class="btn btn-primary float-right" @click="get_MTP_pitchs()">Refresh</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Grade</th>
                            <th>Height</th>
                            <th>Bolts</th>
                            <th>Edit</th>
                            <th>Del</th>
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
                                <button class="btn btn-warning" @click="edit_pitch(pitch.id)">Edit</button>
                            </td>
                            <td>
                                <button class="btn btn-danger" @click="del_pitch(pitch.id)">Delete</button>
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
            ref="edit_pitch_modal"
        />

    </div>
</template>

<script>
    import add_pitch_modal from './Modals/AddPitchModalComponent.vue'
    import edit_pitch_modal from './Modals/EditPitchModalComponent.vue'

    export default {
        props: [
            'mtp_id_prop'
        ],
        components: {
            add_pitch_modal,
            edit_pitch_modal,
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
                .get('/get_mtp/mtp_pitch/get_mtp_pitchs/' + this.mtp_id)
                .then(response => {
                    this.pitchs = response.data
                })
                .catch(error => console.log(error))
            },
            add_pitch(){
                this.$refs.add_pitch_modal.show_modal(this.mtp_id)
            },
            edit_pitch(id){
                this.$refs.edit_pitch_modal.show_modal(id)
            },
            del_pitch(pitch_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/set_mtp/mtp_pitch/del_pitch/'+pitch_id, {
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