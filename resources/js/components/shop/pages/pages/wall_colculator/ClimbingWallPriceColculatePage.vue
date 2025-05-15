<template>
    <div class="container">

        <!-- <VueHtml2pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="true"
            :preview-modal="true"
            :paginate-elements-by-height="1400"
            filename="myPDF"
            :pdf-quality="2"
            :manual-pagination="false"
            pdf-format="a4"
            pdf-orientation="landscape"
            pdf-content-width="800px"
            ref="html2Pdf"
        > -->
        
        <h1 class="page_title">{{ $t('shop.wall.title')}}</h1>
        <div class="bar"><i class="fa fa-calculator" aria-hidden="true"></i></div>
        <h3> <span v-html="this.$siteData.message"></span> </h3>

        <div class="row">
            <div class="col-md-6">

                <div class="row">

                    <form>
                        <div class="row">
                            <p>{{ $t('shop.wall.wall_colculate_title')}}</p>

                            <div class="col-md-4">
                                <p>{{ $t('shop.wall.height')}}</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="number" class="form-control" v-model="height" name="height"  id="height" min="0" :placeholder="$t('shop.wall.height')" @click="set_actyve_image('height')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $t('shop.wall.width')}}</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="number" class="form-control" v-model="width" name="width"  id="width" min="0" :placeholder="$t('shop.wall.width')" @click="set_actyve_image('width')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $t('shop.wall.depth')}}</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="number" class="form-control" v-model="depth" name="depth"  id="depth" min="0" :placeholder="$t('shop.wall.depth')" @click="set_actyve_image('depth')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="!wall_height_for_mat">

                            <p>{{ $t('shop.wall.mat_colculate_title')}}</p>

                            <div class="col-md-4">
                                <p>{{ $t('shop.wall.mat_height')}}</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="number" class="form-control" v-model="mat_height" name="mat_height" min="0.1"  id="mat_height" :placeholder="$t('shop.wall.mat height')" @click="set_actyve_image('mat_height')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $t('shop.wall.mat_width')}}</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="number" class="form-control" v-model="mat_width" name="mat_width" min="0" id="mat_width" :placeholder="$t('shop.wall.mat width')" @click="set_actyve_image('mat_width')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p>{{ $t('shop.wall.mat_depth')}}</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="number" class="form-control" v-model="mat_depth" name="mat_depth" min="0" id="mat_depth" :placeholder="$t('shop.wall.mat depth')" @click="set_actyve_image('mat_depth')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <p>{{ $t('shop.wall.wall_so_height')}}</p>
                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <p>{{ $t('shop.wall.holds')}}</p>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" @click="inserting_holds()" name="hold_insert"  id="hold_insert">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4" v-if="hold_include && wall_square > 0">
                                
                                <p>{{ $t('shop.wall.holds_on_1m2')}}</p>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="number" class="form-control" @click="colculate_holds_price()" v-model="holds_quantyty_for_meter" name="holds_quantyty"  id="holds_quantyty" :placeholder="$t('shop.wall.holds_quantyty')">
                                        </div>
                                    </div>
                                </div>
                            </div> 

                            <div class="col-md-4" v-else-if="wall_square == 0">
                                <p>{{ $t('shop.wall.inser_sizes')}}</p>
                            </div>

                            <div class="col-md-4">
                                <p>{{ $t('shop.wall.vat')}}</p>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" @click="inserting_vat()" name="hold_insert"  id="hold_insert">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                    <button class="btn btn-default btn-send main-btn" @click="colculate_wall_price()">{{ $t('shop.wall.colculate_price')}}</button>
                </div>
                <div class="row">
                    <section slot="pdf-content">
                        <table class="table col-md-12 table table-hover">
                            <!-- <tbody>
                                <tr>
                                    <td>N</td>
                                    <td>{{ $t("guide.route.name") }}</td>
                                    <td>{{ $t("guide.route.height") }}</td>
                                </tr>
                            </tbody> -->
                            <tbody>
                                <tr>
                                    <td>{{ $t('shop.wall.wall_holds_squareness')}}</td>
                                    <td>|</td>
                                    <td>{{wall_square}} (1m2™)</td>
                                </tr>
                                <tr>
                                    <td>{{ $t('shop.wall.wall_price')}}</td>
                                    <td>|</td>
                                    <td>{{wall_price_sum}} $</td>
                                </tr>
                                <tr v-if="!wall_height_for_mat">
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                <tr v-if="!wall_height_for_mat">
                                    <td>{{ $t('shop.wall.wall_price')}}</td>
                                    <td>|</td>
                                    <td>{{mat_square}} (1m2™)</td>
                                </tr>
                                <tr v-if="!wall_height_for_mat">
                                    <td>{{ $t('shop.wall.mat_price') }}</td>
                                    <td>|</td>
                                    <td>{{mat_price_sum}} $</td>
                                </tr>
                                <tr v-if="hold_include">
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                <tr v-if="hold_include">
                                    <td>{{ $t('shop.wall.mat_price') }}</td>
                                    <td>|</td>
                                    <td>{{holds_quantyty_for_meter}}</td>
                                </tr>
                                <tr v-if="hold_include">
                                    <td>{{ $t('shop.wall.total_holds') }}</td>
                                    <td>|</td>
                                    <td>{{holds_total_quantity}}</td>
                                </tr>
                                <tr v-if="hold_include">
                                    <td>{{ $t('shop.wall.total_holds_price') }}</td>
                                    <td>|</td>
                                    <td>{{holds_total_price}} $</td>
                                </tr>
                                <tr v-if="is_vat_include">
                                    <td>{{ $t('shop.wall.vat') }}</td>
                                    <td>|</td>
                                    <td>18 %</td>
                                </tr>
                                <tr v-if="is_vat_include">
                                    <td>{{ $t('shop.wall.vat_price') }}</td>
                                    <td>|</td>
                                    <td>{{vat_price}} $</td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                    <h2 v-if="total_price_sum != 0" class="float_right">{{ $t('shop.wall.total_price') }}</h2>
                    <h2 v-if="total_price_sum != 0" class="float_right">{{ total_price_sum }} $</h2>

                    <!-- <button v-if="total_price_sum != 0" @click="generateReport()" class="btn btn-default btn-send main-btn">Print</button> -->
                </div>
            </div>
            <div class="col-md-6">
                <img v-show="active_image == 'non'" :src="'/public/images/site_img/climbing_wall_img/wall.png'" alt="climbing wall">

                <img v-show="!wall_height_for_mat && active_image == 'height'" :src="'/public/images/site_img/climbing_wall_img/wall-h.png'" alt="climbing wall height">
                <img v-show="!wall_height_for_mat && active_image == 'width'" :src="'/public/images/site_img/climbing_wall_img/wall-w.png'" alt="climbing wall width">
                <img v-show="!wall_height_for_mat && active_image == 'depth'" :src="'/public/images/site_img/climbing_wall_img/wall-d.png'" alt="climbing wall depth">

                <img v-show="wall_height_for_mat && active_image == 'height'" :src="'/public/images/site_img/climbing_wall_img/wall-h no mat.png'" alt="climbing wall height">
                <img v-show="wall_height_for_mat && active_image == 'width'" :src="'/public/images/site_img/climbing_wall_img/wall-w no mat.png'" alt="climbing wall width">
                <img v-show="wall_height_for_mat && active_image == 'depth'" :src="'/public/images/site_img/climbing_wall_img/wall-d no mat.png'" alt="climbing wall depth">

                <img v-show="active_image == 'mat_height'" :src="'/public/images/site_img/climbing_wall_img/mat-h.png'" alt="climbing wall mat height">
                <img v-show="active_image == 'mat_width'" :src="'/public/images/site_img/climbing_wall_img/mat-w.png'" alt="climbing wall mat width">
                <img v-show="active_image == 'mat_depth'" :src="'/public/images/site_img/climbing_wall_img/mat-d.png'" alt="climbing wall mat depth">
            </div>
        </div>

        <metaData 
            :title = " $t('shop.meta.climbing wall') "
            :description = "'Climbing wall colculator. Colculate climbing wall price.'"
            :image = "'/public/images/site_img/climbing_wall_img/wall.png'"
        />
    </div>
</template>

<script>
    // import { VueHtml2pdf } from 'vue-html2pdf'
    import metaData from '../../../items/MetaDataComponent'
    import { coepicients_mixin } from './coepicients_mixin.js';

    export default {
        components: {
            metaData,
            // VueHtml2pdf
        },
        mixins: [
            coepicients_mixin
        ],
        data: function () {
            return {
                coepicients: coepicients_mixin.wall(),
                active_image: 'non',

                wall_height_for_mat: false,
                hold_include: false,

                is_vat_include: false,
                vat_price: 0,

                width: 0,
                height: 0,
                depth: 0,

                wall_square: 0,
                mat_square: 0,

                mat_width: 0,
                mat_height: 0,
                mat_depth: 0,

                holds_quantyty_for_meter: 0,
                holds_total_quantity: 0,
                holds_total_price: 0,

                wall_price_sum: 0,
                mat_price_sum: 0,
                holds_price_sum: 0,
                total_price_sum: 0,
            };
        },
        mounted() {
            //
        },
        methods: {
            generateReport () {
                this.$refs.html2Pdf.generatePdf()
            },

            set_actyve_image(image) { 
                this.active_image = image

                if(this.height > 5){
                    this.wall_height_for_mat = true

                    this.mat_width = 0
                    this.mat_height = 0
                    this.mat_depth = 0

                    this.mat_price_sum = 0
                }
                else if(this.height < 6){
                    this.wall_height_for_mat = false
                }
                
                this.colculate_wall_price()
            },

            inserting_holds(){
                this.hold_include = !this.hold_include
            },

            inserting_vat(){
                this.is_vat_include = !this.is_vat_include
            },

            colculate_holds_price(){
                this.holds_total_quantity = this.wall_square * this.holds_quantyty_for_meter
                this.holds_total_price = this.holds_total_quantity * 15

                this.colculate_total_price()
            },

            colculate_wall_price() {
                if(this.depth > 0 && this.width > 0 && this.height > 0){
                    this.wall_square = this.depth * this.width * this.height
                    this.wall_price_sum = this.wall_square * this.coepicients.wall_squarenes_price.coepicient
                }
                else if(this.width > 0 && this.height > 0 && this.depth == 0){
                    this.wall_square = this.height * this.width
                    this.wall_price_sum = this.wall_square * this.coepicients.wall_squarenes_price.coepicient
                }

                if(!this.wall_height_for_mat){
                    this.colculate_mat_size()
                }
                else{
                    this.colculate_total_price()
                }
            },

            colculate_mat_size() {
                this.mat_width = this.width
                this.mat_square = this.mat_depth * this.mat_width
                this.mat_square = Math.ceil(this.mat_square)

                if(this.depth > 0 && this.width > 0 && this.height > 0){
                    this.depth = Number(this.depth)
                    let procent = (this.depth * 30) / 100
                    this.mat_depth = this.depth + procent

                    this.mat_height = this.height * 0.08
                    this.mat_height = this.mat_height
                }
                else if(this.width > 0 && this.height > 0){

                    this.mat_height = this.height * 0.08
                    this.mat_height = this.mat_height
                    this.mat_depth = 1.5
                }

                this.colculate_mat_price()
            },

            colculate_mat_price() {
                if(this.mat_depth > 0 && this.mat_width > 0 && this.mat_height > 0){
                    this.mat_price_sum = (this.mat_depth * this.mat_width * this.mat_height) * this.coepicients.mat_squarenes_price.coepicient
                }
                else if(this.mat_width > 0 && this.mat_height > 0){
                    this.mat_price_sum = (this.mat_width * this.mat_height) * this.coepicients.mat_squarenes_price.coepicient
                }

                this.colculate_total_price()
            },

            colculate_total_price() {
                this.wall_price_sum = Math.ceil(this.wall_price_sum)
                this.mat_price_sum = Math.ceil(this.mat_price_sum)

                if(this.is_vat_include){
                    this.coepicients.mat_squarenes_price.coepicient
                    this.vat_price = 666
                    this.total_price_sum = this.wall_price_sum + this.mat_price_sum + this.holds_total_price

                    this.total_price_sum = Math.ceil(this.total_price_sum)
                }
                else{
                    this.total_price_sum = this.wall_price_sum + this.mat_price_sum + this.holds_total_price

                    this.total_price_sum = Math.ceil(this.total_price_sum)
                }

            }
        }
    }
</script>
