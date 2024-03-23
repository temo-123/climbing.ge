<template>
    <stack-modal
        :show="is_show_sector_modal"
        title="Spot Sectors"
        @close="close_sector_model()"
        :modal-class="{ [SectorModalClass]: true }"
        :saveButton="{ visible: true }"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
        <pre class="language-vue">
            <div class="root">
                <div class="col-md-12">
                    <div class="row">
                        <h2 v-if="sector_sequence.length > 0">Spot sectors</h2>
                        <SlickList lockAxis="y" v-model="sector_sequence" v-if="sector_sequence.length > 0" tag="table" style="width: 100%">
                            <tr>
                                <td>ID</td>
                                <td>Num</td>
                                <td>Name</td>
                            </tr>
                            <SlickItem v-for="(sector, index) in sector_sequence" :index='index' :key="index" tag="tr">
                                <td>{{ sector.id }}</td>
                                <td>{{ sector.num }}</td>
                                <td>{{ sector.name }}</td>
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
                        @click="save_sector_sequence()"
                    >
                Save sequence
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort     https://www.npmjs.com/package/vue-slicksort/v/2.0.0-alpha.2?activeTab=versions
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
     
    // import { ContainerMixin, ElementMixin } from 'vue-slicksort'; //
    export default {
        props: [
            // 'show_sector_modal',
        ],
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                is_show_sector_modal: false,
                is_loading: false,
                
                sector: [],
                SectorModalClass: 'modal-xxxl',

                sector_sequence: [],
                // new_sector_sequence: [],

                // activ_sector_id: 0,
            }
        },

        mounted() {
            // this.is_show_sector_modal = this.show_sector_modal
        },

        methods: {
            show_spot_sectors_modal(article_id){
                // this.is_show_sector_modal=true

                // if (this.is_show_sector_modal==true) {
                    axios
                    .get('/sector/get_spot_sectors_data_for_model/'+ article_id)
                    .then(response => {
                        this.sector_sequence = response.data
                        this.is_show_sector_modal=true
                    })
                    .catch(
                        error => console.log(error)
                    );
                // }
                // else{
                //     this.sector_sequence = ""
                // }
            },

            close_sector_model(){
                this.is_show_sector_modal = false
                this.sector_sequence = []
                // this.activ_sector_id = 0
            },

            save_sector_sequence(){
                axios
                .post('/sector/save_sector_sequence/', {
                    new_sector_sequence: this.sector_sequence,
                })
                .then((response)=> { 
                    this.close_sector_model()
                })
                .catch(error =>{
                })
            },
        }
    }
</script>

<style>
.image_in_model_tab{
    max-width: 40%;
}
</style>