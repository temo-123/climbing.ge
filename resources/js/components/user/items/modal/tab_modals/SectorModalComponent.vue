<template>
    <StackModal
        v-model="is_show_sector_modal"
        title="Sector"
        @close="close_sector_model()"
        @save="save_routes_sequence"
        :modal-class="{ [SectorModalClass]: true }"
        :saveButton="{ visible: true, title: 'Save sequence' }"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">

        <div v-show="is_show_sector_modal" class="root">
            <div class="col-md-12">

                <!-- Edit link -->
                <div class="row mb-2">
                    <router-link
                        class="btn btn-primary btn-sm"
                        :to="{ name: 'sectorEdit', params: { id: activ_sector_id }, query: { locale: 'en' } }"
                    >
                        <i class="fa fa-pencil"></i> Edit Sector
                    </router-link>
                </div>

                <!-- Sector images -->
                <div v-if="sector_images.length > 0" class="row mb-3">
                    <h4>Images <small class="text-muted">(drag to reorder)</small></h4>
                    <table class="table table-sm drag-table">
                        <thead><tr><td>ID</td><td>Num</td><td>Image</td></tr></thead>
                        <tbody>
                            <tr
                                v-for="(image, index) in sector_images"
                                :key="image.id || index"
                                draggable="true"
                                :class="{ 'drag-over': dragOver === 'images-' + index }"
                                @dragstart="onDragStart('images', index)"
                                @dragover.prevent="dragOver = 'images-' + index"
                                @dragleave="dragOver = null"
                                @drop.prevent="onDrop('images', index)"
                                @dragend="dragOver = null"
                            >
                                <td>{{ image.id }}</td>
                                <td>{{ image.num }}</td>
                                <td><img class="image_in_model_tab" :src="'/public/images/sector_img/'+image.image" alt="image" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Sport routes -->
                <div v-if="sector_routes.length > 0" class="row mb-3">
                    <h4>Sport climbing routes <small class="text-muted">(drag to reorder)</small></h4>
                    <table class="table table-sm drag-table">
                        <thead><tr><td>ID</td><td>Num</td><td>Name</td><td>Grade</td><td>Edit</td></tr></thead>
                        <tbody>
                            <tr
                                v-for="(route, index) in sector_routes"
                                :key="route.id || index"
                                draggable="true"
                                :class="{ 'drag-over': dragOver === 'routes-' + index }"
                                @dragstart="onDragStart('routes', index)"
                                @dragover.prevent="dragOver = 'routes-' + index"
                                @dragleave="dragOver = null"
                                @drop.prevent="onDrop('routes', index)"
                                @dragend="dragOver = null"
                            >
                                <td>{{ route.id }}</td>
                                <td>{{ route.num }}</td>
                                <td>{{ route.name }}</td>
                                <td>{{ route.grade }}{{ route.or_grade ? ' / ' + route.or_grade : '' }}</td>
                                <td><router-link class="btn btn-primary btn-sm" :to="{ name: 'routeEdit', params: { id: route.id } }">Edit</router-link></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Multi-pitches -->
                <div v-if="sector_mtps.length > 0" class="row mb-3">
                    <h4>Multi pitches <small class="text-muted">(drag to reorder)</small></h4>
                    <table class="table table-sm drag-table">
                        <thead><tr><td>ID</td><td>Num</td><td>Name</td><td>Edit</td></tr></thead>
                        <tbody>
                            <tr
                                v-for="(mtp, index) in sector_mtps"
                                :key="mtp.id || index"
                                draggable="true"
                                :class="{ 'drag-over': dragOver === 'mtps-' + index }"
                                @dragstart="onDragStart('mtps', index)"
                                @dragover.prevent="dragOver = 'mtps-' + index"
                                @dragleave="dragOver = null"
                                @drop.prevent="onDrop('mtps', index)"
                                @dragend="dragOver = null"
                            >
                                <td>{{ mtp.id }}</td>
                                <td>{{ mtp.num }}</td>
                                <td>{{ mtp.name }}</td>
                                <td><router-link class="btn btn-primary btn-sm" :to="{ name: 'MTPEdit', params: { id: mtp.id } }">Edit</router-link></td>
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
        data(){
            return {
                is_show_sector_modal: false,
                sector: [],
                SectorModalClass: 'modal-xxxl',
                sector_routes: [],
                sector_mtps: [],
                sector_images: [],
                activ_sector_id: 0,
                dragOver: null,
                dragSrc: null,
            }
        },

        methods: {
            show_sector_modal(sector_id){
                this.activ_sector_id = sector_id
                this.is_show_sector_modal = true
                axios
                    .get('/get_sector/get_sector_data_for_model/' + sector_id)
                    .then(response => {
                        this.sector = response.data
                        this.sector_images = this.sector.images || []
                        this.sector_routes = this.sector.routes || []
                        this.sector_mtps   = this.sector.mtps   || []
                    })
                    .catch(error => console.log(error))
            },

            close_sector_model(){
                this.is_show_sector_modal = false
                this.activ_sector_id = 0
                this.$nextTick(() => {
                    this.sector_routes = []
                    this.sector_mtps   = []
                    this.sector_images = []
                })
            },

            onDragStart(list, index) {
                this.dragSrc = { list, index }
            },

            onDrop(list, toIndex) {
                if (!this.dragSrc || this.dragSrc.list !== list) return
                const from = this.dragSrc.index
                if (from === toIndex) return
                const arr = list === 'routes' ? this.sector_routes
                          : list === 'mtps'   ? this.sector_mtps
                          :                     this.sector_images
                const moved = arr.splice(from, 1)[0]
                arr.splice(toIndex, 0, moved)
                this.dragSrc = null
                this.dragOver = null
            },

            save_routes_sequence(){
                axios
                    .post('/set_sector/routes_sequence/', {
                        routes_sequence:         this.sector_routes,
                        mtp_sequence:            this.sector_mtps,
                        sector_images_sequence:  this.sector_images,
                    })
                    .then(() => this.close_sector_model())
                    .catch(error => console.log(error))
            },
        }
    }
</script>

<style scoped>
.drag-table tbody tr {
    cursor: grab;
    user-select: none;
}
.drag-table tbody tr.drag-over {
    background: #d0e8ff;
    outline: 2px dashed #0d6efd;
}
.image_in_model_tab {
    max-width: 40%;
}
</style>
