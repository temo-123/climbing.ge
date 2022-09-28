<template>
    <stack-modal
        :show="show_modal"
        title="Sector"
        @close="show_modal=false"
        :modal-class="{ [SectorModalClass]: true }"
        :saveButton="{ visible: true }"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
        <pre class="language-vue">
            <div class="root">
                <div class="col-md-12">
                    <div class="row">
                        <img v-for="sector_image in sector_images" :key="sector_image.id" :src="'/public/images/sector_img/'+sector_image.image" alt="image" :style="'width:' + sector_images_size + '%'">
                    </div>
                    <div class="row">
                        <h2 v-if="sector_routes != ''">Sport climbing routes</h2>
                        <SlickList lockAxis="y" v-model="sector_routes" v-if="sector_routes != ''" tag="table" style="width: 100%">
                            <tr>
                                <td>ID</td>
                                <td>Num</td>
                                <td>Name</td>
                                <td>Grade</td>
                                <td>Height</td>
                                <td>Bolts</td>
                                <td>Bolter</td>
                                <td>First ascent</td>
                                <td>Edit</td>
                            </tr>
                            <SlickItem v-for="(route, index) in sector_routes" :index='index' :key="index" tag="tr">
                                <td>{{ route.id }}</td>
                                <td>{{ route.num }}</td>
                                <td>{{ route.name }}</td>
                                <td v-if="route.or_grade != NULL">{{route.grade}} / {{ route.or_grade }}</td>
                                <td v-if="route.or_grade == NULL">{{route.grade}}</td>
                                <td>{{ route.height }}</td>
                                <td>{{ route.bolts }}</td>
                                <td>{{ route.bolter }}</td>
                                <td>{{ route.first_ascent }}</td>
                                <td><a :href="table_2_edit_url+route.id" class="btn btn-primary" type="submit">Edit</a></td>
                            </SlickItem>
                        </SlickList>

                        <h2 v-if="sector_mtps != ''">Mupli pitchs</h2>

                        <SlickList lockAxis="y" v-model="sector_mtps" v-if="sector_mtps != ''" tag="table" style="width: 100%">
                            <tr>
                                <td>ID</td>
                                <td>Num</td>
                                <td>Name</td>
                            </tr>
                            <SlickItem v-for="(mtp, index) in sector_mtps" :index='index' :key="index" tag="tr">
                                <td>{{ mtp.id }}</td>
                                <td>{{ mtp.num }}</td>
                                <td>{{ mtp.name }}</td>
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
        props: [
            'show_sector_modal',
        ],
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                show_modal: false,
                routes_sequence: [],
                mtp_sequence: [],
                sector_images: [],
                sector_routes: [],
                sector_mtps: [],
                SectorModalClass: 'modal-xxxl',
            }
        },

        mounted() {
            this.show_modal = this.show_sector_modal
        },

        methods: {
            // show_sector_model(sector_id){
            //     this.show_sector_modal=true
            // },


            show_sector_model(sector_id){
                this.show_sector_modal=true

                if (this.show_sector_modal==true) {
                    axios
                    .get('/routes_and_sectors/get_routes_for_model/'+ sector_id)
                    .then(response => {
                        this.sector_routes = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );

                    axios
                    .get('/routes_and_sectors/get_sector_image/'+ sector_id)
                    .then(response => {
                        this.sector_images = response.data.sector_images
                        this.sector_images_size = response.data.sector_images_size
                    })
                    .catch(
                        error => console.log(error)
                    );

                    axios
                    .get('/routes_and_sectors/get_mtp_for_model/'+ sector_id)
                    .then(response => {
                        this.sector_mtps = response.data
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

            save_routes_sequence(){
                axios
                .post('../routes_and_sectors/routes_sequence/', {
                    routes_sequence: this.sector_routes,
                    mtp_sequence: this.sector_mtps,
                })
                .then((response)=> { 
                    this.show_sector_modal = false
                })
                .catch(error =>{
                })
            },
        }
    }
</script>

<style>

</style>