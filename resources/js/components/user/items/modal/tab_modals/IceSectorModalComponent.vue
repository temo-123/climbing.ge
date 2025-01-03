<template>
    <stack-modal
        :show="is_show_ice_sector_modal"
        title="Sector"
        @close="close_sector_model()"
        :modal-class="{ [SectorModalClass]: true }"
        :saveButton="{ visible: true }"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
        <pre class="language-vue">
            <div class="root">
                <div class="col-md-12">
                    <div class="row">
                        <router-link class="btn btn-primary" :to="{ name: 'iceSectorEdit', params: { id: activ_sector_id } }" v-if="$can('edit', 'sector')" >Edit This Sector</router-link>
                    </div>
                    <div class="row">
                        <!-- <img v-for="image in sector_images" :key="image.id" :src="'/public/images/sector_img/'+image.image" alt="image"> -->
                        <SlickList lockAxis="y" v-model="sector_images" v-if="sector_images.length > 0" tag="table" style="width: 100%">
                            <tr>
                                <td>ID</td>
                                <td>Num</td>
                                <td>Image</td>
                            </tr>
                            <SlickItem v-for="(image, index) in sector_images" :index='index' :key="index" tag="tr">
                                <td>{{ image.id }}</td>
                                <td>{{ image.num }}</td>
                                <td><img class="image_in_model_tab" :src="'/public/images/ice_sector_img/'+image.image" alt="image" /></td>
                            </SlickItem>
                        </SlickList>
                    </div>
                    <div class="row">
                        <h2 v-if="sector_routes.length > 0">Sport climbing routes</h2>
                        <SlickList lockAxis="y" v-model="sector_routes" v-if="sector_routes.length > 0" tag="table" style="width: 100%">
                            <tr>
                                <td>ID</td>
                                <td>Num</td>
                                <td>Name</td>
                                <td>Grade</td>
                                <td>Edit</td>
                            </tr>
                            <SlickItem v-for="(route, index) in sector_routes" :index='index' :key="index" tag="tr">
                                <td>{{ route.id }}</td>
                                <td>{{ route.num }}</td>
                                <td>{{ route.name }}</td>
                                <td v-if="route.or_grade != NULL">{{route.grade}} / {{ route.or_grade }}</td>
                                <td v-if="route.or_grade == NULL">{{route.grade}}</td>
                                <td><router-link class="btn btn-primary" :to="{ name: 'routeEdit', params: { id: route.id } }" >Edit</router-link></td>
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
                        @click="save_routes_sequence()"
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
            // 'show_ice_sector_modal',
        ],
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                is_show_ice_sector_modal: false,
                
                sector: [],
                SectorModalClass: 'modal-xxxl',

                sector_routes: [],
                sector_mtps: [],
                sector_images: [],

                activ_sector_id: 0,
            }
        },

        mounted() {
            // this.is_show_ice_sector_modal = this.show_ice_sector_modal
        },

        methods: {
            show_ice_sector_modal(sector_id){
                this.is_show_ice_sector_modal=true

                this.activ_sector_id = sector_id

                if (this.is_show_ice_sector_modal==true) {
                    axios
                    .get('/ice_sectors/get_sector_data_for_model/'+ sector_id)
                    .then(response => {
                        this.sector = response.data
                        
                        this.sector_images = this.sector.images
                        this.sector_routes = this.sector.routes
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
                else{
                    this.sector_routes = ""
                    this.sector_images = ""
                    this.sector_mtp = ""
                }
            },

            close_sector_model(){
                this.is_show_ice_sector_modal = false
                this.activ_sector_id = 0
            },

            save_routes_sequence(){
                axios
                .post('/ice_sectors/routes_sequence/', {
                    routes_sequence: this.sector_routes,
                    mtp_sequence: this.sector_mtps,
                    sector_images_sequence: this.sector_images,
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