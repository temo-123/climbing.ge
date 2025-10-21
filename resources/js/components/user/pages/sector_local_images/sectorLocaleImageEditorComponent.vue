<template>
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <header class="page-header">
                        <h1>Sector Locale Image Editor</h1>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h3>Sectors</h3>
                    <table class="table table-hover" id="sectors-table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>ID</th>
                                <th>Sector name</th>
                                <th>Existing Layouts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="sector in availableSectors" :key="sector.id">
                                <td>
                                    <input type="checkbox" :checked="selectedSectors.includes(sector.id)" @change="toggleSector(sector.id)">
                                </td>
                                <td>{{sector.id}}</td>
                                <td>{{sector.name}}</td>
                                <td>
                                    <div v-for="layout in getSectorLayouts(sector.id)" :key="layout.id" class="layout-item">
                                        {{ getLayoutName(layout) }} <button class="btn btn-sm btn-outline-primary ml-1" @click="selectLayout(layout.id)">Select</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="layouts.length === 0" class="text-center mt-3">
                        <button class="btn btn-primary" @click="createLayout">Create Layout for Selected Sectors</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 editor-section">
                    <div class="row">
                        <div class="col-md-12"  >
        <Editor
          ref="editorComponent"
          :image_prop="'/public/images/sector_local_img/' + sector_sector_local_image_pitchs_for_modal.image.image"
          :json_prop="canvasData"
          :related_jsons="related_jsons"
          @canvas_data="handleCanvasData"
        />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 button-section">
                    <button class="btn btn-primary pool-left" @click="saveChanges">Save</button>
                    <button class="btn btn-secondary pool-right" @click="goBack">Go Back</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
  import Editor from '../../items/canvas/EditorComponent.vue'
    export default {
        components: {
          Editor,
            StackModal,
            // SlickItem,
            // SlickList,
        },
        data(){
            return {
                is_show_sector_local_image_modal: false,
                sector_sector_local_image_pitchs_for_modal:[],
                MTPModalClass: '',
                canvasData: null,
                layouts: [],
                availableSectors: [],
                selectedSectors: [],
                related_jsons: null,
            }
        },

        mounted() {
            this.show_sector_local_image_modal()

            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        methods: {
            show_sector_local_image_modal(){
                if (!this.is_show_sector_local_image_modal) {
                    axios
                    .get('/sector_local_img/get_sector_local_img_for_modal/' + this.$route.params.id)
                    .then(response => {
                        this.sector_sector_local_image_pitchs_for_modal = response.data
                        this.availableSectors = response.data.sectors
                        this.related_jsons = response.data.related_jsons
                        this.loadLayouts()
                        this.loadCanvasData()

                        this.is_show_sector_local_image_modal=true
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
                else{
                    this.sector_sector_local_image_pitchs_for_modal = ""
                }
            },
            loadLayouts() {
                axios.get('/sector_local_img/get_layouts/' + this.$route.params.id)
                    .then(response => {
                        this.layouts = response.data.layouts
                        // Update layout names based on their sectors
                        this.layouts.forEach(layout => {
                            layout.name = this.getLayoutName(layout);
                        });
                    })
                    .catch(error => console.log(error));
            },
            toggleSector(sectorId) {
                const index = this.selectedSectors.indexOf(sectorId);
                if (index > -1) {
                    this.selectedSectors.splice(index, 1);
                } else {
                    this.selectedSectors.push(sectorId);
                }
            },
            getSectorLayouts(sectorId) {
                return this.layouts.filter(layout => layout.sectors.some(sector => sector.id === sectorId));
            },
            getLayoutName(layout) {
                // Find the first sector name for this layout
                if (layout.sectors && layout.sectors.length > 0) {
                    return layout.sectors[0].name;
                }
                return `Layout ${layout.id}`;
            },
            selectLayout(layoutId) {
                axios.get('/sector_local_img/get_layout/' + layoutId)
                    .then(response => {
                        const layout = response.data.layout;
                        this.canvasData = layout.json;
                        this.selectedSectors = layout.sectors.map(s => s.id);
                    })
                    .catch(error => console.log(error));
            },
            createLayout() {
                if (this.selectedSectors.length > 0) {
                    axios.post('/sector_local_img/create_layout/' + this.$route.params.id, {
                        selectedSectors: this.selectedSectors
                    })
                        .then(response => {
                            alert('Layout created successfully!');
                            this.loadLayouts(); // Reload layouts after creation
                        })
                        .catch(error => {
                            console.log(error);
                            alert('Error creating layout.');
                        });
                } else {
                    alert('No sectors selected.');
                }
            },
            loadCanvasData() {
                // Load initial canvas data if available
                if (this.related_jsons && this.related_jsons.length > 0) {
                    this.canvasData = this.related_jsons[0].json; // Assuming first json
                }
            },
            handleCanvasData(data) {
                this.canvasData = data;
            },
            saveChanges() {
                if (this.canvasData && this.selectedSectors.length > 0) {
                    axios.post('/sector_local_img/save_canvas_data/' + this.$route.params.id, {
                        canvasData: this.canvasData,
                        selectedSectors: this.selectedSectors
                    })
                        .then(response => {
                            alert('Changes saved successfully!');
                            this.loadLayouts(); // Reload layouts after save
                        })
                        .catch(error => {
                            console.log(error);
                            alert('Error saving changes.');
                        });
                } else {
                    alert('No canvas data or sectors selected.');
                }
            },
            goBack() {
                this.$router.go(-1);
            }
        }
    }
</script>

<style scoped>
.page-header {
    margin-bottom: 20px;
    text-align: center;
}

.editor-section {
    margin-bottom: 20px;
}

.image-section {
    margin-bottom: 20px;
    text-align: center;
}

.table-section {
    margin-bottom: 20px;
}

.button-section {
    text-align: center;
    margin-top: 20px;
}

.button-section .btn {
    margin: 0 10px;
}

.layout-item {
    margin-bottom: 2px;
    font-size: 0.9em;
    color: #666;
}

.badge {
    margin-right: 5px;
}

.table-active {
    background-color: #f8f9fa;
}

.table-header-buttons {
    display: flex;
    gap: 10px;
}
</style>
