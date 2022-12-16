<template>
    <stack-modal
            :show="is_show_mtp_modal"
            title="Multy-pitch"
            @close="is_show_mtp_modal=false"
            :modal-class="{ [MTPModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
            <pre class="language-vue">
                <div class="root">
                    <div class="col-md-12">
                        <div class="row">
                            <SlickList lockAxis="y" v-model="sector_mtp_pitchs_for_modal" tag="table" style="width: 100%">
                                <tr>
                                    <td>ID</td>
                                    <td>Num</td>
                                    <td>Name</td>
                                    <td>Grade</td>
                                    <td>Height</td>
                                    <td>Bolts</td>
                                    <td>Bolter</td>
                                    <td>First ascent</td>
                                </tr>
                                <SlickItem v-for="(pitch, index) in sector_mtp_pitchs_for_modal" :index='index' :key="index" tag="tr">
                                    <td>{{ pitch.id }}</td>
                                    <td>{{ pitch.num }}</td>
                                    <td>{{ pitch.name }}</td>
                                    <td>{{ pitch.grade }}</td>
                                    <td>{{ pitch.height }}</td>
                                    <td>{{ pitch.bolts }}</td>
                                    <td>{{ pitch.bolter }}</td>
                                    <td>{{ pitch.first_ascent }}</td>
                                </SlickItem>
                            </SlickList>
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
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                is_show_mtp_modal: false,
                sector_mtp_pitchs_for_modal:[],
                MTPModalClass: ''
            }
        },

        mounted() {
            
        },

        methods: {

            show_mtp_modal(mtp_id){
                this.is_show_mtp_modal=true

                if (this.is_show_mtp_modal==true) {
                    axios
                    .get('../api/mtp/mtp_pitch/get_mtp_pitchs_for_model/'+ mtp_id)
                    .then(response => {
                        this.sector_mtp_pitchs_for_modal = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
                else{
                    this.sector_mtp_pitchs_for_modal = ""
                }
            },
            save_pitchs_sequence(){
                axios
                .post('../api/mtp/mtp_pitch/pitchs_sequence/', {
                    pitchs_sequence: this.sector_mtp_pitchs_for_modal,
                })
                .then((response)=> { 
                    this.is_show_mtp_modal = false
                })
                .catch(error =>{
                })
            },
        }
    }
</script>

<style>

</style>