<template>
    <div class="container">
        <div class="row">
            <!-- <img :src="'../../images/region_sectors_img/'+this.region_image" :alt="'../images/region_sectors_img/'+this.region_imag"> -->
            <expandable-image
                class=""
                :src="'../../images/region_sectors_img/'+this.region_image"
                :alt="'../images/region_sectors_img/'+this.region_imag"
            ></expandable-image>
        </div>
        <div class="row">
            <div class="col-md-12" v-for="sector in this.sectors" :key="sector.id">
                <div class="row">
                    <h2 style="font-size: 160%">Sector name - <strong>{{ sector['sectors']['name'] }}</strong></h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <img v-if="sector['sectors']['in_shade_after_10'] == 1" class="sun_svg" :src="'../../images/svg/in shade after 10.00 am.svg'" alt="In shade after 10.00 am" title="In shade after 10.00 am">
                
                        <img v-if="sector['sectors']['in_shade_after_15'] == 1" class="sun_svg" :src="'../../images/svg/in shade after 15.00 pm.svg'" alt="In shade after 15.00 pm" title="In shade after 15.00 pm">
                
                        <img v-if="sector['sectors']['in_the_shade_befornoon'] == 1" class="sun_svg" :src="'../../images/svg/in shade befor noon.svg'" alt="In shade befor noon" title="In shade befor noon">
                
                        <img v-if="sector['sectors']['in_the_shade_afternoon'] == 1" class="sun_svg" :src="'../../images/svg/in shade in the afternoon.svg'" alt="In shade in the afternoon" title="In shade in the afternoon">
                
                        <img v-if="sector['sectors']['all_day_in_shade'] == 1" class="sun_svg" :src="'../../images/svg/in the shade whole day.svg'" alt="In the shade whole day" title="In the shade whole day">
                
                        <img v-if="sector['sectors']['all_day_in_sun'] == 1" class="sun_svg" :src="'../../images/svg/in the sun the whole day.svg'" alt="In the sun the whole day" title="In the sun the whole day">
                        
                    </div>
                    <div class="col-md-6">
                        <img v-if="sector['sectors']['overhang'] == 1" class="relief_svg" :src="'../../images/svg/overhang.svg'" alt="Overhang" title="Overhang">
                
                        <img v-if="sector['sectors']['slabby'] == 1" class="relief_svg" :src="'../../images/svg/slabby.svg'" alt="Slabby" title="Slabby">
                
                        <img v-if="sector['sectors']['vertical'] == 1" class="relief_svg" :src="'../../images/svg/vertical.svg'" alt="Vertical" title="Vertical">
                    </div>
                </div>
                <div class="row"  v-if="sector['sectors']['sector_img'] != ''">
                    <!-- <img v-for="sector_img in sector['sectors']['sector_img']" :key="sector_img.image" :style="'width:' + sector_img['sector_img_size'] + '%'" :src="'../../images/sector_img/'+sector_img.image" :alt="sector_img.image"> -->
                    <expandable-image v-for="sector_img in sector['sectors']['sector_img']" :key="sector_img.image"
                        :class="'sector_images sector_images_'+sector['sectors']['sectors_img_ouent']"
                        style=""
                        :src="'../../images/sector_img/'+sector_img.image"
                        :alt="sector_img.image"
                    ></expandable-image>
                </div>
                <div class="row" v-if="sector['routes'] != ''">
                    <table class="table col-md-12 table table-hover" >
                        <tbody>
                            <tr>
                                <td>N</td>
                                <td>Name</td>
                                <td>Height</td>
                                <td>Bolts</td>
                                <td>Gread FR</td>
                                <td class="display-none-720px">Gread YDS</td> 
                                <td>Info</td> 
                            </tr>
                        </tbody>
                        <tbody>
                            <tr v-for="route in sector['routes']" :key="route.id"> 
                                <td>{{ route.num }}</td>
                                <td>
                                    <a @click="show_model()">{{ route.name }}dd</a>
                                </td>
                                <td>{{ route.height }}</td>

                                <td v-if="route.category == '1'">{{ route.bolts }}</td>
                                <td v-if="route.category == NULL">?</td>
                                <td v-if="route.category == '2'">Top rope</td>
                                <td v-if="route.category == '3'">Tred</td>
                                <td v-if="route.category == '4'">bouldering</td>

                                <td>{{ route.grade_fr }} <div v-if="route.or_grade_fr == NULL"></div></td>
                                <td class="display-none-720px">{{ route.grade_yds }} <div v-if="route.or_grade_yds == NULL"></div></td>
                                <td>
                                    <a @click="show_route_madel()" style="margin-top: -5%; font-size: 120%;"><i class="fa fa-info-circle"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class ='row' v-for="mtp_pitch in sector['mtps']" :key="mtp_pitch.id">
                    <div class ='row'>
                        <div class='col-xs-6 col-md-6 col-lg-6'>
                                <h3>{{ mtp_pitch['mtp_num'] }}) <strong>{{ mtp_pitch['mtp_name'] }}</strong></h3>
                        </div>
                        
                        <div class='col-md-6'>
                                <a @click='show_mtp_madel()' data-toggle="modal" data-target="#squarespaceModal_mtp_info_">
                                    <h4><strong>More information</strong></h4>
                                </a>
                        </div>
                    </div>

                    <table class="table col-md-12 table table-hover" id="dev-table">
                        <tbody>
                            <tr>
                                <td>N</td>
                                <td>Name</td>
                                <td>Height</td>
                                <td>Bolts</td>
                                <td>Gread FR</td>
                                <td class="display-none-720px">Gread YDS</td> 
                                <td>Info</td> 
                            </tr>
                        </tbody>
                        <tbody>
                            <tr v-for="pitch in mtp_pitch['mtp_pitchs']" :key="pitch.id">  
                                <td>{{ pitch.pitch_num }}</td>
                                <td>{{ pitch.pitch_name }}</td>
                                <td>{{ pitch.pitch_height }}</td>

                                <td>{{ pitch.pitch_bolts }}</td>

                                <td>{{ pitch.pitch_grade_fr }} <div v-if="pitch.or_grade_fr == NULL"></div></td>
                                <td class="display-none-720px">{{ pitch.pitch_grade_yds }} <div v-if="pitch.or_grade_yds == NULL"></div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>





        <stack-modal
            :show="show_route_modal"
            title="Sector"
            @close="show_route_modal=false"
            :modal-class="{ [SectorModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
            <pre class="language-vue">
                <div class="root">
                    <div class="col-md-12">
                        it is your model
                    </div>
                </div>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="alert('test')"
                        >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="show_mtp_modal"
                title="Edit roles"
                @close="show_mtp_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <div slot="modal-footer">
                <div class="modal-footer">
                    footer
                </div>
            </div>
        </stack-modal>

        <div>
      <b-button v-b-modal.modal-center>Vue Bootstrap Modal Example</b-button>

      <b-modal id="modal-center" centered title="Vue Bootstrap Centered Modal Example">
        <p class="my-4">Knowing yourself is the beginning of all wisdom.</p>
      </b-modal>
    </div>  
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    // import VueExpandableImage from 'vue-expandable-image' //https://github.com/TahaSh/vue-expandable-image

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        props:[
            'region_id',

        ],
        data: function () {
            return {
                sectors: [],
                region_image: '',

                size: '20%',

                show_route_modal: false,
                show_mtp_modal: false,
                modalClass: '',
            };
        },
        mounted() {
            this.get_sectors_data()
            this.get_region_image()
        },
        methods: {
            show_model(id){
                this.show_route_modal=true
            },
            show_mtp_madel(route_id){
                this.show_mtp_modal=true
            },
            get_sectors_data() {
                axios
                .get('../get_sectors_and_routes/' + this.region_id, {
                })
                .then(response => {
                    // console.log(response.data[0]['sectors']['all_day_in_shade']);
                    this.sectors = response.data
                })
                .catch(error =>{
                    // console.log('error');
                })
            },
            get_region_image() {
                axios
                .get('../get_region_image/' + this.region_id, {
                })
                .then(response => {
                    this.region_image = response.data[0]['climbing_area_image']
                })
                .catch(error =>{

                })
            },
        }
    }
</script> 

<style scoped>
.modal-dialog {
    /* right: auto; */
    /* left: 50%; */
    /* width: 600px; */
    /* padding-top: 30px; */
    /* padding-bottom: 30px; */
    margin-top: 50%;
}

.sector_images{
    float: left;
    margin: 0.25%;
}


.sector_images_1{
    width: 99%;
}
.sector_images_2{
    width: 49%;
}
.sector_images_3{
    width: 33%;
}
.sector_images_4{
    width: 24.5%;
}
.sector_images_5{
    width: 19.5%;
}
.sector_images_6{
    width: 16.0%;
}
.sector_images_7{
    width: 14,0%;
}
.sector_images_8{
    width: 12.0%;
}
.sector_images_9{
    width: 10.5%;
}
.sector_images_10{
    width: 9.5%;
}
</style>