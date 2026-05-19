<template>
    <StackModal
        v-model="is_show_sector_modal"
        title="Spot Sectors"
        @close="close_sector_model()"
        :modal-class="{ [SectorModalClass]: true }"
        :saveButton="{ visible: true, title: 'Save sequence', onClick: save_sector_sequence }"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
        <div class="root">
            <div class="col-md-12">
                <div class="row">
                    <h2 v-if="sector_sequence.length > 0">Spot sectors</h2>
                    <table v-if="sector_sequence.length > 0" style="width: 100%">
                        <thead>
                            <tr>
                                <td></td>
                                <td>ID</td>
                                <td>Num</td>
                                <td>Name</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(sector, index) in sector_sequence"
                                :key="sector.id"
                                draggable="true"
                                @dragstart="onDragStart(index)"
                                @dragover.prevent="onDragOver(index)"
                                @dragleave="onDragLeave"
                                @drop="onDrop"
                                :class="{ 'drag-over-row': dragOverIndex === index && dragSrcIndex !== index }"
                                style="cursor: grab;"
                            >
                                <td><i class="fa fa-bars" aria-hidden="true"></i></td>
                                <td>{{ sector.id }}</td>
                                <td>{{ sector.num }}</td>
                                <td>{{ sector.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </StackModal>
</template>

<script>
    export default {
        props: [],
        data(){
            return {
                is_show_sector_modal: false,
                is_loading: false,

                sector: [],
                SectorModalClass: 'modal-xxxl',

                sector_sequence: [],

                dragSrcIndex: null,
                dragOverIndex: null,
            }
        },

        methods: {
            show_spot_sectors_modal(article_id){
                axios
                .get('/get_sector/get_spot_sectors_data_for_model/'+ article_id)
                .then(response => {
                    this.sector_sequence = response.data
                    this.is_show_sector_modal=true
                })
                .catch(
                    error => console.log(error)
                );
            },

            close_sector_model(){
                this.is_show_sector_modal = false
                this.sector_sequence = []
            },

            save_sector_sequence(){
                axios
                .post('/set_sector/save_sector_sequence/', {
                    new_sector_sequence: this.sector_sequence,
                })
                .then((response)=> {
                    this.close_sector_model()
                })
                .catch(error =>{
                })
            },

            onDragStart(index) {
                this.dragSrcIndex = index;
            },
            onDragOver(index) {
                this.dragOverIndex = index;
            },
            onDragLeave() {
                this.dragOverIndex = null;
            },
            onDrop() {
                if (this.dragSrcIndex === null || this.dragOverIndex === null || this.dragSrcIndex === this.dragOverIndex) {
                    this.dragSrcIndex = null;
                    this.dragOverIndex = null;
                    return;
                }
                const items = [...this.sector_sequence];
                const [moved] = items.splice(this.dragSrcIndex, 1);
                items.splice(this.dragOverIndex, 0, moved);
                this.sector_sequence = items;
                this.dragSrcIndex = null;
                this.dragOverIndex = null;
            },
        }
    }
</script>

<style>
.image_in_model_tab{
    max-width: 40%;
}
.drag-over-row {
    background-color: #e8d5f5;
    border-top: 2px solid #7427bb;
}
</style>
