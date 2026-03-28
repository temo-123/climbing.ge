<template>
    <StackModal
        v-model="is_show_sector_modal"
        title="Sector"
        @close="close_sector_model()"
        :modal-class="{ [SectorModalClass]: true }"
        :saveButton="{ visible: true, title: 'Save sequence', onClick: save_routes_sequence }"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
        <div class="root">
            <div class="col-md-12">
                <div class="row">
                    <router-link class="btn btn-primary" :to="{ name: 'sectorEdit', params: { id: activ_sector_id } }" v-if="$can('edit', 'sector')" >Edit This Sector</router-link>
                </div>
                <div class="row">
                    <SlickList lockAxis="y" v-model="sector_images" v-if="sector_images.length > 0" tag="table" style="width: 100%">
                        <tr>
                            <td>ID</td>
                            <td>Num</td>
                            <td>Image</td>
                        </tr>
                        <SlickItem v-for="(image, index) in sector_images" :index='index' :key="index" tag="tr">
                            <td>{{ image.id }}</td>
                            <td>{{ image.num }}</td>
                            <td><img class="image_in_model_tab" :src="'/public/images/sector_img/'+image.image" alt="image" /></td>
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
                            <td v-if="route.or_grade != null">{{route.grade}} / {{ route.or_grade }}</td>
                            <td v-if="route.or_grade == null">{{route.grade}}</td>
                            <td><router-link class="btn btn-primary" :to="{ name: 'routeEdit', params: { id: route.id } }" >Edit</router-link></td>
                        </SlickItem>
                    </SlickList>


                    <h2 v-if="sector_mtps.length > 0">Multi pitchs</h2>
                    <SlickList lockAxis="y" v-model="sector_mtps" v-if="sector_mtps.length > 0" tag="table" style="width: 100%">
                        <tr>
                            <td>ID</td>
                            <td>Num</td>
                            <td>Name</td>
                            <td>Edit</td>
                        </tr>
                        <SlickItem v-for="(mtp, index) in sector_mtps" :index='index' :key="index" tag="tr">
                            <td>{{ mtp.id }}</td>
                            <td>{{ mtp.num }}</td>
                            <td>{{ mtp.name }}</td>
                            <td><router-link class="btn btn-primary" :to="{ name: 'MTPEdit', params: { id: mtp.id } }" >Edit</router-link></td>
                        </SlickItem>
                    </SlickList>
                </div>
            </div>
        </div>
    </StackModal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort';

    export default {
        props: [],
        components: {
            SlickItem,
            SlickList,
        },
        data(){
            return {
                is_show_sector_modal: false,
                
                sector: [],
                SectorModalClass: 'modal-xxxl',

                sector_routes: [],
                sector_mtps: [],
                sector_images: [],

                activ_sector_id: 0,
            }
        },

        mounted() {
        },

        methods: {
            show_sector_modal(sector_id){
                this.is_show_sector_modal=true
                this.activ_sector_id = sector_id

                axios
                .get('/get_sector/get_sector_data_for_model/'+ sector_id)
                .then(response => {
                    this.sector = response.data
                    
                    this.sector_images = this.sector.images
                    this.sector_routes = this.sector.routes
                    this.sector_mtps = this.sector.mtps
                })
                .catch(error => console.log(error));
            },

            close_sector_model(){
                this.is_show_sector_modal = false
                this.activ_sector_id = 0
            },

            save_routes_sequence(){
                axios
                .post('/set_sector/routes_sequence/', {
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
