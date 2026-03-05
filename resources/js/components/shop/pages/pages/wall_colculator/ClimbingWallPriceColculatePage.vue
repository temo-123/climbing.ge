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
        <!-- <h3> <span v-html="this.$siteData.data.message"></span> </h3> -->

        <div class="row">
            <div class="col-md-6">

                <div class="row">

                    <form>
                        <!-- Wall Type Selection -->
                        <div class="row">
                            <div class="col-md-12">
                                <label>{{ $t('shop.wall.wall_type') }}</label>
                                <small class="form-text text-muted">{{ $t('shop.wall.wall_type_help') }}</small>
                                <div class="form-group">
                                    <select class="form-control" v-model="wall_type" @change="colculate_wall_price(); set_wall_type_image()">
                                        <option value="" disabled>{{ $t('shop.wall.select_option') }}</option>
                                        <option v-for="(value, key) in coepicients.wall_types" :key="key" :value="key">
                                            {{ value.name }}
                                        </option>
                                    </select>
                                </div>
                                <div v-if="wall_type && coepicients.wall_types[wall_type]" class="wall-type-description">
                                    {{ translatedDescription }}
                                </div>
                            </div>
                        </div>

                        <!-- Wall Dimensions -->
                        <div class="row">
                            <div class="col-md-12">
                                <p class="section-title">{{ $t('shop.wall.wall_dimensions') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label>{{ $t('shop.wall.height') }}</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" v-model="height" name="height"  id="height" min="0" :placeholder="$t('shop.wall.height_placeholder')" @click="set_actyve_image('height')">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>{{ $t('shop.wall.width') }}</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" v-model="width" name="width"  id="width" min="0" :placeholder="$t('shop.wall.width_placeholder')" @click="set_actyve_image('width')">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>{{ $t('shop.wall.depth') }}</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" v-model="depth" name="depth"  id="depth" min="0" :placeholder="$t('shop.wall.depth_placeholder')" @click="set_actyve_image('depth')">
                                </div>
                            </div>
                        </div>

                        <!-- Safety Mat -->
                        <div class="row" v-if="!wall_height_for_mat">
                            <div class="col-md-12">
                                <p class="section-title">{{ $t('shop.wall.mat_section_title') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label>{{ $t('shop.wall.mat_height') }}</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" v-model="mat_height" name="mat_height" min="0.1"  id="mat_height" :placeholder="$t('shop.wall.mat_height_placeholder')" @click="set_actyve_image('mat_height')">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>{{ $t('shop.wall.mat_width') }}</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" v-model="mat_width" name="mat_width" min="0" id="mat_width" :placeholder="$t('shop.wall.mat_width_placeholder')" @click="set_actyve_image('mat_width')">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>{{ $t('shop.wall.mat_depth') }}</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" v-model="mat_depth" name="mat_depth" min="0" id="mat_depth" :placeholder="$t('shop.wall.mat_depth_placeholder')" @click="set_actyve_image('mat_depth')">
                                </div>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> {{ $t('shop.wall.no_mat_hint') }}
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Additional Options -->
                        <div class="row">
                            <div class="col-md-12">
                                <p class="section-title">{{ $t('shop.wall.add_options') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="checkbox-inline">
                                    <input type="checkbox" @click="inserting_holds()" name="hold_insert" id="hold_insert">
                                    {{ $t('shop.wall.add_holds') }}
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="checkbox-inline">
                                    <input type="checkbox" @click="inserting_vat()" name="vat_insert" id="vat_insert">
                                    {{ $t('shop.wall.include_vat') }}
                                </label>
                            </div>
                        </div>

                        <div class="row" v-if="hold_include && wall_square > 0" style="margin-top: 15px;">
                            <div class="col-md-6">
                                <label>{{ $t('shop.wall.holds_per_sqm') }}</label>
                                <div class="form-group">
                                    <input type="range" class="form-control-range" min="1" max="20" v-model="holds_quantyty_for_meter" @input="colculate_holds_price()">
                                    <span class="help-block">{{ holds_quantyty_for_meter }} {{ $t('shop.wall.holds_per_unit') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="wall_square == 0" style="margin-top: 15px;">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i> {{ $t('shop.wall.enter_dimensions_first') }}
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <button class="btn btn-default btn-send main-btn btn-block" @click="colculate_wall_price()">
                                <i class="fa fa-calculator"></i> {{ $t('shop.wall.calculate_price') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <section slot="pdf-content">
                        <div class="price-summary" v-if="total_price_sum > 0">
                            <h4>{{ $t('shop.wall.price_summary') }}</h4>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td>{{ $t('shop.wall.wall_area') }}</td>
                                        <td class="text-right">{{wall_square}} m²</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $t('shop.wall.base_price') }}</td>
                                        <td class="text-right">{{wall_price_sum}} $</td>
                                    </tr>
                                    <tr v-if="!wall_height_for_mat">
                                        <td>{{ $t('shop.wall.mat_area') }}</td>
                                        <td class="text-right">{{mat_square}} m²</td>
                                    </tr>
                                    <tr v-if="!wall_height_for_mat">
                                        <td>{{ $t('shop.wall.mat_price') }}</td>
                                        <td class="text-right">{{mat_price_sum}} $</td>
                                    </tr>
                                    <tr v-if="hold_include">
                                        <td>{{ $t('shop.wall.total_holds') }}</td>
                                        <td class="text-right">{{holds_total_quantity}}</td>
                                    </tr>
                                    <tr v-if="hold_include">
                                        <td>{{ $t('shop.wall.holds_price') }}</td>
                                        <td class="text-right">{{holds_total_price}} $</td>
                                    </tr>
                                    <tr v-if="is_vat_include" class="info">
                                        <td><strong>{{ $t('shop.wall.vat') }} ({{ coepicients.vat.coepicient }}%)</strong></td>
                                        <td class="text-right"><strong>{{vat_price}} $</strong></td>
                                    </tr>
                                    <tr class="success total-row">
                                        <td><strong>{{ $t('shop.wall.total_price') }}</strong></td>
                                        <td class="text-right"><strong>{{ total_price_sum }} $</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
            <div class="col-md-6">
                <div class="wall-preview">
                    <!-- Wall Type Image -->
                    <img v-if="wall_type && active_image === 'wall_type'" 
                         :src="getWallTypeImage()" 
                         :alt="getWallTypeName()" 
                         class="img-responsive wall-type-image">

                    <!-- <img v-show="active_image == 'non'" :src="'/public/images/site_img/climbing_wall_img/wall.png'" :alt="$t('shop.wall.image.wall')" class="img-responsive">

                    <img v-show="!wall_height_for_mat && active_image == 'height'" :src="'/public/images/site_img/climbing_wall_img/wall-h.png'" :alt="$t('shop.wall.image.wall_height')" class="img-responsive">
                    <img v-show="!wall_height_for_mat && active_image == 'width'" :src="'/public/images/site_img/climbing_wall_img/wall-w.png'" :alt="$t('shop.wall.image.wall_width')" class="img-responsive">
                    <img v-show="!wall_height_for_mat && active_image == 'depth'" :src="'/public/images/site_img/climbing_wall_img/wall-d.png'" :alt="$t('shop.wall.image.wall_depth')" class="img-responsive">

                    <img v-show="wall_height_for_mat && active_image == 'height'" :src="'/public/images/site_img/climbing_wall_img/wall-h no mat.png'" :alt="$t('shop.wall.image.wall_no_mat')" class="img-responsive">
                    <img v-show="wall_height_for_mat && active_image == 'width'" :src="'/public/images/site_img/climbing_wall_img/wall-w no mat.png'" :alt="$t('shop.wall.image.wall_no_mat')" class="img-responsive">
                    <img v-show="wall_height_for_mat && active_image == 'depth'" :src="'/public/images/site_img/climbing_wall_img/wall-d no mat.png'" :alt="$t('shop.wall.image.wall_no_mat')" class="img-responsive">

                    <img v-show="active_image == 'mat_height'" :src="'/public/images/site_img/climbing_wall_img/mat-h.png'" :alt="$t('shop.wall.image.mat_height')" class="img-responsive">
                    <img v-show="active_image == 'mat_width'" :src="'/public/images/site_img/climbing_wall_img/mat-w.png'" :alt="$t('shop.wall.image.mat_width')" class="img-responsive">
                    <img v-show="active_image == 'mat_depth'" :src="'/public/images/site_img/climbing_wall_img/mat-d.png'" :alt="$t('shop.wall.image.mat_depth')" class="img-responsive"> -->
                </div>

                <!-- Help Section -->
                <div class="help-section" style="margin-top: 20px;">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="fa fa-question-circle"></i> {{ $t('shop.wall.need_help') }}</h4>
                        </div>
                        <div class="panel-body">
                            <p>{{ $t('shop.wall.help_text') }}</p>
                            <ul>
                                <li>{{ $t('shop.wall.help_dimensions') }}</li>
                                <li>{{ $t('shop.wall.help_mat') }}</li>
                                <li>{{ $t('shop.wall.help_holds') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <metaData
            :title = "$t('shop.meta.climbing_wall_title')"
            :description = "$t('shop.meta.climbing_wall_description')"
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

                holds_quantyty_for_meter: 10,
                holds_total_quantity: 0,
                holds_total_price: 0,

                wall_price_sum: 0,
                mat_price_sum: 0,
                total_price_sum: 0,

                // Wall type selection
                wall_type: '',
            };
        },
        computed: {
            translatedDescription() {
                if (this.wall_type && this.coepicients.wall_types[this.wall_type]) {
                    const desc = this.coepicients.wall_types[this.wall_type].description;
                    
                    // Check if it looks like a $siteData reference
                    if (desc && desc.startsWith('$siteData.')) {
                        const path = desc.replace('$siteData.', '');
                        // The path is relative to $siteData.data, so we need to strip 'data.' prefix if present
                        const propertyPath = path.replace(/^data\./, '');
                        
                        if (this.$siteData.data && this.$siteData.data[propertyPath]) {
                            return this.$siteData.data[propertyPath];
                        }
                        return '';
                    }
                    
                    // Check if it looks like a translation key (contains dots)
                    if (desc && desc.includes('.')) {
                        return this.$t(desc);
                    }
                    return desc;
                }
                return '';
            }
        },
        methods: {
            generateReport () {
                this.$refs.html2Pdf.generatePdf()
            },

            set_wall_type_image() {
                if (this.wall_type && this.coepicients.wall_types[this.wall_type]) {
                    this.active_image = 'wall_type';
                }
            },

            getWallTypeImage() {
                if (this.wall_type && this.coepicients.wall_types[this.wall_type]) {
                    const image = this.coepicients.wall_types[this.wall_type].image;
                    // Check if it's an external URL
                    if (image.startsWith('http')) {
                        return image;
                    }
                    // Otherwise prepend local path
                    return '/public/images/site_img/climbing_wall_img/' + image;
                }
                return '/public/images/site_img/climbing_wall_img/wall.png';
            },

            getWallTypeName() {
                if (this.wall_type && this.coepicients.wall_types[this.wall_type]) {
                    return this.coepicients.wall_types[this.wall_type].name;
                }
                return '';
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
                this.colculate_wall_price()
            },

            inserting_vat(){
                this.is_vat_include = !this.is_vat_include
                this.colculate_wall_price()
            },

            colculate_holds_price(){
                this.holds_total_quantity = Math.round(this.wall_square * this.holds_quantyty_for_meter)
                this.holds_total_price = this.holds_total_quantity * this.coepicients.hold_midle_price.coepicient

                this.colculate_total_price()
            },

            colculate_wall_price() {
                // Calculate base wall square and price
                if(this.depth > 0 && this.width > 0 && this.height > 0){
                    this.wall_square = this.depth * this.width * this.height
                    this.wall_price_sum = this.wall_square * this.coepicients.wall_squarenes_price.coepicient
                }
                else if(this.width > 0 && this.height > 0 && this.depth == 0){
                    this.wall_square = this.height * this.width
                    this.wall_price_sum = this.wall_square * this.coepicients.wall_squarenes_price.coepicient
                }

                // Apply wall type multiplier
                if(this.wall_type && this.coepicients.wall_types[this.wall_type]){
                    this.wall_price_sum = this.wall_price_sum * this.coepicients.wall_types[this.wall_type].coepicient
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
                this.holds_total_price = Math.ceil(this.holds_total_price)

                // Calculate subtotal before VAT
                let subtotal = this.wall_price_sum + this.mat_price_sum + this.holds_total_price

                if(this.is_vat_include){
                    this.vat_price = Math.ceil(subtotal * (this.coepicients.vat.coepicient / 100))
                    this.total_price_sum = subtotal + this.vat_price
                }
                else{
                    this.vat_price = 0
                    this.total_price_sum = subtotal
                }
            },
        }
    }
</script>

<style scoped>
.wall-type-description {
    margin-top: 10px;
    padding: 12px 15px;
    background-color: #f0f7ff;
    border-left: 4px solid #337ab7;
    border-radius: 6px;
    font-size: 16px;
    line-height: 1.5;
    color: #333;
}

.wall-preview {
    position: relative;
}

.wall-type-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin-bottom: 15px;
}

.img-responsive {
    max-width: 100%;
    height: auto;
}

.panel-info {
    border-color: #bce8f1;
}

.panel-info > .panel-heading {
    background-color: #d9edf7;
    border-color: #bce8f1;
    color: #31708f;
}
</style>

