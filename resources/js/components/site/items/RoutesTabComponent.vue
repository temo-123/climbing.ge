<template>
    <div class="container">
        <!-- image
            <expandable-image 
                :class="'sector_images'"
                style=""
                :src="'http://climbing.loc/images/sector_img/2021-08-07-17-08-32.jpg'"
                :alt="'test'"
            ></expandable-image>
        image -->
        <div class="row" v-for="area in climbing_area" :key="area">
            <div class="row">
                <h2 style="font-size: 160%">{{__ ('sector name')}} - <strong>{{ area.sectors.name }}</strong></h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <img v-if="area.sectors.in_shade_after_10" class="sun_svg" :src="'../images/svg/in shade after 10.00 am.svg'" alt="In shade after 10.00 am" title="In shade after 10.00 am">
                    <img v-if="area.sectors.in_shade_after_15" class="sun_svg" :src="'../images/svg/in shade after 15.00 pm.svg'" alt="In shade after 15.00 pm" title="In shade after 15.00 pm">
                    <img v-if="area.sectors.in_the_shade_befornoon" class="sun_svg" :src="'../images/svg/in shade befor noon.svg'" alt="In shade befor noon" title="In shade befor noon">
                    <img v-if="area.sectors.in_the_shade_afternoon" class="sun_svg" :src="'../images/svg/in shade in the afternoon.svg'" alt="In shade in the afternoon" title="In shade in the afternoon">
                    <img v-if="area.sectors.all_day_in_shade" class="sun_svg" :src="'../images/svg/in the shade whole day.svg'" alt="In the shade whole day" title="In the shade whole day">
                    <img v-if="area.sectors.all_day_in_sun" class="sun_svg" :src="'../images/svg/in the sun the whole day.svg'" alt="In the sun the whole day" title="In the sun the whole day">
                </div>
                <div class="col-md-6">
                    <img v-if="area.sectors.overhang" class="relief_svg" :src="'../images/svg/overhang.svg'" alt="Overhang" title="Overhang">
                    <img v-if="area.sectors.slabby" class="relief_svg" :src="'../images/svg/slabby.svg'" alt="Slabby" title="Slabby">
                    <img v-if="area.sectors.vertical" class="relief_svg" :src="'../images/svg/vertical.svg'" alt="Vertical" title="Vertical">
                </div>
            </div>

            <span v-html="area.sectors.text"></span>
            
            <!-- <div class="row">
                <img v-for="image in area.sectors.sector_img" :key="image.id" :style="'width:' + image.sector_img_size + '%'" :src="'../images/sector_img/' + image.image" :alt="image.sector_img_size">
            </div> -->

            <expandable-image v-for="image in area.sectors.sector_img" :key="image.id"
                :class="'sector_images sector_images_'+area.sectors.sector_img.length"
                style=""
                :src="'../images/sector_img/'+image.image"
                :alt="image.image"
            ></expandable-image>
        
            <table class="table col-md-12 table table-hover"  v-if="area.routes.length > 0">
                <tbody>
                    <tr>
                        <td>N</td>
                        <td>{{__ ('route_tab name')}}</td>
                        <td>{{__ ('route_tab height')}}</td>
                        <td>{{__ ('route_tab bolts')}}</td>
                        <td>{{__ ('route_tab grade fr')}}</td>
                        <td class="display-none-720px">{{__ ('route_tab grade yds')}}</td> 
                        <td>Info</td> 
                    </tr>
                </tbody>
                    <tbody>
                        <tr v-for="route in area.routes" :key="route.id"> 
                            <td>{{ route.num }}</td>
                            <td @click="show_route_model()">{{ route.name }}</td>
                            <td>{{ route.height }}</td>
                            <td>{{ route.bolts }}</td>
                            <td v-if="route.or_grade_fr != NULL">{{ route.grade_fr }}/{{ route.or_grade_fr }}</td>
                            <td v-else>{{ route.grade_fr }}</td>
                            <td class="display-none-720px" v-if="route.or_grade_yds != NULL">{{ route.grade_yds }}/{{route.or_grade_yds}}</td>
                            <td class="display-none-720px" v-else>{{ route.grade_yds }}</td>
                            <td  @click="show_route_model()">
                                <a style="margin-top: -5%; font-size: 120%;"><i class="fa fa-info" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    </tbody>
            </table>


            <div class ='row' :if="area.mtps.length > 0" v-for="mtp in area.mtps" :key="mtp.id">
                <div class='col-xs-8 col-md-8 col-lg-8'>
                    <h3>{{__ ('mtp name')}} - <strong>{{ mtp.mtp_name }}</strong></h3>
                </div>
                
                <div class='col-md-4'>
                    <a @click='show_mtp_madel()' data-toggle="modal" data-target="#squarespaceModal_mtp_info_">
                    <h4><strong>More information</strong></h4>
                    </a>
                </div>

                <table class="table col-md-12 table table-hover" id="dev-table">
                    <tbody>
                        <tr>
                            <td>N</td>
                            <td>{{__ ('route_tab name')}}</td>
                            <td>{{__ ('route_tab height')}}</td>
                            <td>{{__ ('route_tab bolts')}}</td>
                            <td>{{__ ('route_tab grade fr')}}</td>
                            <td class="display-none-720px">{{__ ('route_tab grade yds')}}</td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr v-for="pitch in mtp.mtp_pitchs" :key="pitch.pitch_id">  
                            <td>{{ pitch.pitch_num }}</td>
                            <td>{{ pitch.pitch_name }}</td>
                            <td>{{ pitch.pitch_height }}</td>
                            <td>{{ pitch.pitch_bolts }}</td>
                            <td v-if="pitch.pitch_or_grade_fr != NULL">{{ pitch.pitch_grade_fr }}/{{ pitch.pitch_or_grade_fr }}</td>
                            <td v-else>{{ pitch.pitch_grade_fr }}</td>
                            <td class="display-none-720px" v-if="pitch.pitch_or_grade_yds != NULL">{{ pitch.pitch_grade_yds }}/{{pitch.pitch_or_grade_yds}}</td>
                            <td class="display-none-720px" v-else>{{ pitch.pitch_grade_yds }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- <stack-modal
            :show="show_route_modal"
            title="Route"
            @close="show_route_modal=false"
            :modal-class="{ [ModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
            <div class="model-body">
                <div class="container">
                    <div class="row">
                        it is your route model
                    </div>
                    <div class="row">
                        <h2 class="text-center">Posts</h2>
                    </div>
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    footer
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="show_mtp_modal"
                title="Molty pitch"
                @close="show_mtp_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <div class="model-body">
                <div class="container">
                    <div class="row">
                        it is your mtp model
                    </div>
                    <div class="row">
                        <h2 class="text-center">Posts</h2>
                    </div>
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    footer
                </div>
            </div>
        </stack-modal> -->


    </div>
</template>

<script>
    import VueExpandableImage from 'vue-expandable-image' //https://github.com/TahaSh/vue-expandable-image
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    // import StackModal from '../../../src/components/StackModal'

    export default {
        components: {
            // SlickItem,
            // SlickList,
            // StackModal,
            VueExpandableImage
        },
        props: [
            'article_id',
        ],
        data: function () {
            return {
                climbing_area: [],

                show_route_modal: false,
                show_mtp_modal: false,
                modalClass: '',
            };
        },
        mounted() {
            this.get_outdoor_routes()
        },
        methods: {
            show_route_model(id){
                this.show_route_modal=true
            },
            show_mtp_madel(route_id){
                this.show_mtp_modal=true
            },
            get_outdoor_routes(){
                axios
                .get('../api/sector/' + this.article_id)
                .then(response => {
                    this.climbing_area = response.data
                })
                .catch(error =>{
                })
            }
        }
    }
</script>
<style>
    /* @import "https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"; */
</style>

<style lang="scss">
    .fade {
        opacity: 1;
        background: #000000a3 !important;
    }
    body.modal-open{
        margin-right: 0;
    }
    .modal-dialog {
        margin-top: 15em;
    }
    @media screen and (min-width: 768px){
        .modal-dialog {
            width: 75% !important;
        }
    }
    .modal-footer {
        text-align: left;
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
        width: 32.6%;
    }
    .sector_images_4{
        width: 24.1%;
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