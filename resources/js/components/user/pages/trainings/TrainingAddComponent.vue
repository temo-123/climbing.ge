<template>
    <div class="tabs">

        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="button" class="btn btn-primary" @click="$router.push({name: 'trainingsList'})">{{ $t('common.back') }}</button>
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="button" class="btn btn-primary" @click="save()">{{ $t('common.save') }}</button>
            </div>
        </div>

        <div class="row" v-show="!is_loading && errors && Object.keys(errors).length">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <li v-for="(msgs, field) in errors" :key="field">{{ msgs[0] }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <input type="radio" id="tab1" :value="1" v-model="tab_num">
                        <label for="tab1">{{ $t('common.global_info') }}</label>
                    </div>
                    <div class="col">
                        <input type="radio" id="tab2" :value="2" v-model="tab_num">
                        <label for="tab2">{{ $t('admin.training.tab_steps') }}</label>
                    </div>
                    <div class="col">
                        <input type="radio" id="tab3" :value="3" v-model="tab_num">
                        <label for="tab3">{{ $t('common.georgian_text') }}</label>
                    </div>
                </div>
            </div>

            <!-- Tab 1: Global info -->
            <div class="col-md-12" v-show="tab_num == 1">

                <div class="form-group">
                    <label>{{ $t('admin.training.mode_label') }}</label>
                    <select v-model="trainingMode" class="form-control" @change="onModeChange">
                        <option value="custom">{{ $t('admin.training.mode_custom') }}</option>
                        <option value="shop_product">{{ $t('admin.training.mode_shop_product') }}</option>
                    </select>
                </div>

                <div class="form-group" v-if="trainingMode === 'shop_product'">
                    <label>{{ $t('admin.training.product_label') }}</label>
                    <input type="text" v-model="productSearchQuery" @input="searchProducts" class="form-control" :placeholder="$t('admin.training.product_search_placeholder')">
                    <ul v-if="productResults.length" class="list-group product-search-results">
                        <li v-for="p in productResults" :key="p.id" class="list-group-item list-group-item-action" style="cursor:pointer" @click="selectProduct(p)">{{ p.title }}</li>
                    </ul>
                    <small v-if="form.product_id" class="text-muted d-block mt-1">{{ $t('admin.training.selected_product') }}: {{ productSearchQuery }}</small>
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.name_label') }} <span class="text-danger">*</span></label>
                    <input type="text" v-model="form.name" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ $t('common.description') }}</label>
                    <textarea v-model="form.description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ $t('admin.training.type_label') }} <span class="text-danger">*</span></label>
                        <select v-model="form.type" class="form-control">
                            <option v-for="t in types" :key="t" :value="t">{{ $t('admin.training.type_' + t) }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ $t('admin.training.difficulty_label') }}</label>
                        <select v-model="form.difficulty" class="form-control">
                            <option v-for="d in difficulties" :key="d" :value="d">{{ $t('admin.training.difficulty_' + d) }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.target_muscle_label') }}</label>
                    <input type="text" v-model="form.target_muscle" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.coach_tip_label') }}</label>
                    <textarea v-model="form.coach_tip" class="form-control" rows="2"></textarea>
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.image_url_label') }}</label>
                    <input type="text" v-model="form.image_url" class="form-control">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>{{ $t('admin.training.hang_time_label') }}</label>
                        <input type="number" min="0" v-model.number="form.hang_time" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ $t('admin.training.rest_time_label') }}</label>
                        <input type="number" min="0" v-model.number="form.rest_time" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ $t('admin.training.reps_label') }}</label>
                        <input type="number" min="0" v-model.number="form.reps" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ $t('admin.training.sets_label') }}</label>
                        <input type="number" min="0" v-model.number="form.sets" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.recover_time_label') }}</label>
                    <input type="number" min="0" v-model.number="form.recover_time" class="form-control">
                </div>

                <div class="form-group clearfix row">
                    <label class="col-md-2 control-label">{{ $t('admin.common.publish') }}</label>
                    <div class="col-md-10">
                        <select class="form-control" v-model="form.is_published">
                            <option :value="0">{{ $t('admin.common.not_public') }}</option>
                            <option :value="1">{{ $t('admin.common.public') }}</option>
                        </select>
                    </div>
                </div>

            </div>

            <!-- Tab 2: Steps -->
            <div class="col-md-12" v-show="tab_num == 2">
                <p v-if="form.steps.length == 0" class="text-muted">{{ $t('admin.training.no_steps_hint') }}</p>

                <div v-for="(step, index) in form.steps" :key="index" class="card mb-3 p-3">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>{{ $t('admin.training.step_phase_label') }}</label>
                            <select v-model="step.phase" class="form-control">
                                <option v-for="p in phases" :key="p" :value="p">{{ $t('admin.training.phase_' + p) }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>{{ $t('admin.training.step_label_label') }}</label>
                            <input type="text" v-model="step.label" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
                            <label>{{ $t('admin.training.step_duration_label') }}</label>
                            <input type="number" min="0" v-model.number="step.duration_seconds" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
                            <label>{{ $t('admin.training.image_url_label') }}<span v-if="trainingMode === 'shop_product'" class="text-danger">*</span></label>
                            <input type="file" accept="image/*" @change="onStepImageChange(index, $event)" class="form-control">
                            <img v-if="step.image_url" :src="step.image_url" alt="" style="max-height:60px;margin-top:4px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ $t('admin.training.step_instructions_label') }}</label>
                        <textarea v-model="step.instructions" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" @click="removeStep(index)">{{ $t('admin.training.remove_step_btn') }}</button>
                </div>

                <button type="button" class="btn btn-secondary" @click="addStep()">{{ $t('admin.training.add_step_btn') }}</button>
            </div>

            <!-- Tab 3: Georgian translation -->
            <div class="col-md-12" v-show="tab_num == 3">
                <p class="text-muted">{{ $t('admin.training.ka_translation_hint') }}</p>

                <div class="form-group">
                    <label>{{ $t('admin.training.name_label') }} (KA)</label>
                    <input type="text" v-model="form.translations.ka.name" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ $t('common.description') }} (KA)</label>
                    <textarea v-model="form.translations.ka.description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.target_muscle_label') }} (KA)</label>
                    <input type="text" v-model="form.translations.ka.target_muscle" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.coach_tip_label') }} (KA)</label>
                    <textarea v-model="form.translations.ka.coach_tip" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'TrainingAddComponent',
    data() {
        return {
            tab_num: 1,
            is_loading: false,
            types: ['fingerboard', 'campus', 'flexibility', 'strength', 'endurance'],
            difficulties: ['easy', 'medium', 'hard'],
            phases: ['prepare', 'hang', 'rest', 'recover', 'work', 'stretch'],
            trainingMode: 'custom',
            productSearchQuery: '',
            productResults: [],
            form: {
                name: '',
                description: '',
                type: 'fingerboard',
                difficulty: 'medium',
                target_muscle: '',
                coach_tip: '',
                image_url: '',
                product_id: null,
                hang_time: 7,
                rest_time: 3,
                reps: 6,
                sets: 4,
                recover_time: 180,
                is_published: 1,
                steps: [],
                translations: {
                    ka: { name: '', description: '', coach_tip: '', target_muscle: '' },
                },
            },
            errors: {},
        }
    },
    mounted() {
        document.querySelector('body').style.marginLeft = '0';
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },
    methods: {
        addStep() {
            this.form.steps.push({ phase: 'work', label: '', duration_seconds: 30, image_url: '', instructions: '', _imageFile: null });
        },
        removeStep(index) {
            this.form.steps.splice(index, 1);
        },
        onModeChange() {
            if (this.trainingMode === 'custom') {
                this.form.product_id = null;
                this.productSearchQuery = '';
                this.productResults = [];
            }
        },
        searchProducts() {
            clearTimeout(this._productSearchTimeout);
            if (!this.productSearchQuery) {
                this.productResults = [];
                return;
            }
            this._productSearchTimeout = setTimeout(() => {
                axios.get('/set_training/search_products', { params: { query: this.productSearchQuery } })
                    .then(response => {
                        this.productResults = response.data.products || [];
                    });
            }, 300);
        },
        selectProduct(p) {
            this.form.product_id = p.id;
            this.productSearchQuery = p.title;
            this.productResults = [];
        },
        onStepImageChange(index, event) {
            const file = event.target.files[0];
            if (file) {
                this.form.steps[index]._imageFile = file;
                this.form.steps[index].image_url = URL.createObjectURL(file);
            }
        },
        buildStepsFormData(formData) {
            this.form.steps.forEach((step, i) => {
                formData.append(`steps[${i}][phase]`, step.phase);
                if (step.label) formData.append(`steps[${i}][label]`, step.label);
                formData.append(`steps[${i}][duration_seconds]`, step.duration_seconds);
                if (step.instructions) formData.append(`steps[${i}][instructions]`, step.instructions);
                if (step._imageFile) {
                    formData.append(`steps[${i}][image]`, step._imageFile);
                } else if (step.image_url) {
                    formData.append(`steps[${i}][image_url]`, step.image_url);
                }
            });
        },
        buildFormData() {
            const formData = new FormData();
            formData.append('name', this.form.name);
            if (this.form.description) formData.append('description', this.form.description);
            formData.append('type', this.form.type);
            formData.append('difficulty', this.form.difficulty);
            if (this.form.target_muscle) formData.append('target_muscle', this.form.target_muscle);
            if (this.form.coach_tip) formData.append('coach_tip', this.form.coach_tip);
            if (this.form.image_url) formData.append('image_url', this.form.image_url);
            if (this.form.product_id) formData.append('product_id', this.form.product_id);
            formData.append('hang_time', this.form.hang_time);
            formData.append('rest_time', this.form.rest_time);
            formData.append('reps', this.form.reps);
            formData.append('sets', this.form.sets);
            formData.append('recover_time', this.form.recover_time);
            formData.append('is_published', this.form.is_published);
            Object.keys(this.form.translations.ka).forEach(k => {
                if (this.form.translations.ka[k]) formData.append(`translations[ka][${k}]`, this.form.translations.ka[k]);
            });
            this.buildStepsFormData(formData);
            return formData;
        },
        save() {
            this.errors = {};

            if (this.trainingMode === 'shop_product') {
                const missingImage = this.form.steps.length === 0 || this.form.steps.some(s => !s.image_url && !s._imageFile);
                if (missingImage) {
                    this.errors = { steps: [this.$t('admin.training.step_image_required_error')] };
                    return;
                }
            }

            this.is_loading = true;

            const hasNewStepImage = this.form.steps.some(s => s._imageFile);
            const payload = hasNewStepImage ? this.buildFormData() : this.form;

            axios
            .post('/set_training/create_training', payload)
            .then(() => {
                this.$router.push({ name: 'trainingsList' });
            })
            .catch(error => {
                if (error.response && error.response.status == 422) {
                    this.errors = error.response.data.errors || {};
                }
            })
            .finally(() => this.is_loading = false);
        },
    }
}
</script>

<style scoped>
.tabs {
    flex-wrap: wrap;
}
.tabs label {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    margin-right: 0.2rem;
    cursor: pointer;
    background: #ccced0;
    font-weight: bold;
    transition: background ease 0.2s;
}
.tabs input[type="radio"] {
    display: none;
}
.tabs input[type="radio"]:checked + label {
    background: #fff;
    border: 1px solid #ccc !important;
}
</style>
