<template>
    <StackModal
        v-model="is_show_sector_modal"
        :title="$t('admin.users.spot_sectors_title')"
        @close="close_sector_model()"
        @save="save_sector_sequence"
        :modal-class="{ [SectorModalClass]: true }"
        :saveButton="{ visible: true, title: $t('admin.users.save_sequence_btn') }"
        :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-primary': true } }">
        <div class="root">
            <div class="col-md-12">
                <div class="row" v-for="(list, list_index) in sector_lists" :key="list.key">
                    <h2>
                        {{ list.image ? (list.image.title || $t('admin.users.sector_local_image_group', { id: list.image.id })) : $t('admin.users.sectors_without_local_image') }}
                        <span v-if="list.image" class="float-end">
                            <button class="btn btn-sm btn-outline-secondary" :disabled="list_index === 0" @click="move_list(list_index, -1)"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
                            <button class="btn btn-sm btn-outline-secondary" :disabled="list_index >= image_lists_count - 1" @click="move_list(list_index, 1)"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                        </span>
                    </h2>
                    <table style="width: 100%">
                        <thead>
                            <tr>
                                <td></td>
                                <td>{{ $t('common.id') }}</td>
                                <td>{{ $t('admin.users.col_num') }}</td>
                                <td>{{ $t('common.name') }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(sector, index) in list.sectors"
                                :key="sector.id"
                                draggable="true"
                                @dragstart="onDragStart(list_index, index)"
                                @dragover.prevent="onDragOver(list_index, index)"
                                @dragleave="onDragLeave"
                                @drop="onDrop"
                                :class="{ 'drag-over-row': dragOverList === list_index && dragOverIndex === index && dragSrcIndex !== index }"
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

                // One list per sector local image (public page groups), then sectors with no image
                sector_lists: [],

                dragSrcList: null,
                dragSrcIndex: null,
                dragOverList: null,
                dragOverIndex: null,
            }
        },

        computed: {
            image_lists_count() {
                return this.sector_lists.filter(list => list.image).length
            },
        },

        methods: {
            show_spot_sectors_modal(article_id){
                axios
                .get('/get_sector/get_spot_sectors_data_for_model/'+ article_id)
                .then(response => {
                    this.sector_lists = response.data.groups.map(group => ({ key: 'image_' + group.image.id, image: group.image, sectors: group.sectors }))
                    if (response.data.ungrouped.length) {
                        this.sector_lists.push({ key: 'ungrouped', image: null, sectors: response.data.ungrouped })
                    }
                    this.is_show_sector_modal=true
                })
                .catch(
                    error => console.log(error)
                );
            },

            close_sector_model(){
                this.is_show_sector_modal = false
                this.sector_lists = []
            },

            save_sector_sequence(){
                // A sector shown on two local images keeps its first position
                const seen = new Set()
                const new_sector_sequence = this.sector_lists
                    .flatMap(list => list.sectors)
                    .filter(sector => !seen.has(sector.id) && seen.add(sector.id))

                axios
                .post('/set_sector/save_sector_sequence/', {
                    new_sector_sequence: new_sector_sequence,
                })
                .then((response)=> {
                    this.close_sector_model()
                })
                .catch(error =>{
                })
            },

            move_list(list_index, direction) {
                const lists = [...this.sector_lists]
                const [moved] = lists.splice(list_index, 1)
                lists.splice(list_index + direction, 0, moved)
                this.sector_lists = lists
            },

            onDragStart(list_index, index) {
                this.dragSrcList = list_index;
                this.dragSrcIndex = index;
            },
            onDragOver(list_index, index) {
                this.dragOverList = list_index;
                this.dragOverIndex = index;
            },
            onDragLeave() {
                this.dragOverIndex = null;
            },
            onDrop() {
                // Sectors move only inside their own group — group membership comes from the local image
                if (this.dragSrcIndex !== null && this.dragOverIndex !== null
                    && this.dragSrcList === this.dragOverList && this.dragSrcIndex !== this.dragOverIndex) {
                    const items = [...this.sector_lists[this.dragSrcList].sectors];
                    const [moved] = items.splice(this.dragSrcIndex, 1);
                    items.splice(this.dragOverIndex, 0, moved);
                    this.sector_lists[this.dragSrcList].sectors = items;
                }
                this.dragSrcList = null;
                this.dragSrcIndex = null;
                this.dragOverList = null;
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
    border-top: 2px solid var(--cg-accent);
}
</style>
