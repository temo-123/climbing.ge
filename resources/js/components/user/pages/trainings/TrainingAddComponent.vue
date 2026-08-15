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
                    <div class="col">
                        <input type="radio" id="tab4" :value="4" v-model="tab_num">
                        <label for="tab4">{{ $t('admin.training.tab_products') }}</label>
                    </div>
                </div>
            </div>

            <!-- Tab 1: Global info -->
            <div class="col-md-12" v-show="tab_num == 1">

                <div class="alert alert-warning mode-warning-box">
                    <label class="font-weight-bold">⚠ {{ $t('admin.training.mode_label') }}</label>
                    <select v-model="trainingMode" class="form-control" @change="onModeChange">
                        <option value="custom">{{ $t('admin.training.mode_custom') }}</option>
                        <option value="shop_product">{{ $t('admin.training.mode_shop_product') }}</option>
                    </select>
                    <p class="mb-0 mt-2 small">
                        {{ trainingMode === 'shop_product' ? $t('admin.training.mode_warning_shop') : $t('admin.training.mode_warning_custom') }}
                    </p>
                </div>

                <div class="form-group" v-if="trainingMode === 'shop_product'">
                    <label>{{ $t('admin.training.category_label') }}</label>
                    <select v-model="selectedCategoryId" class="form-control" @change="onCategoryChange">
                        <option :value="0" disabled>{{ $t('admin.training.select_category') }}</option>
                        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.us_name }}</option>
                    </select>

                    <label class="mt-2" v-if="selectedCategoryId">{{ $t('admin.training.subcategory_label') }}</label>
                    <select v-if="selectedCategoryId" v-model="selectedSubcategoryId" class="form-control" @change="onSubcategoryChange">
                        <option :value="0" disabled>{{ $t('admin.training.select_subcategory') }}</option>
                        <option v-for="s in subcategories" :key="s.id" :value="s.id">{{ s.us_name }}</option>
                    </select>

                    <label class="mt-2" v-if="selectedSubcategoryId">{{ $t('admin.training.product_label') }}</label>
                    <select v-if="selectedSubcategoryId" v-model="form.product_id" class="form-control">
                        <option :value="null" disabled>{{ $t('admin.training.select_product') }}</option>
                        <option v-for="p in products" :key="p.id" :value="p.id">{{ p.title }}</option>
                    </select>
                    <small v-if="selectedSubcategoryId && !products.length" class="text-muted d-block mt-1">{{ $t('admin.training.no_products_in_subcategory') }}</small>
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
                    <single_image_add
                        :title_prop="$t('admin.training.cover_image_label')"
                        :crop_ratio_prop="{ width: 16, height: 9 }"
                        @update_single_image="mainImageFile = $event"
                    />
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
                <div class="alert alert-info">
                    <p class="mb-2">{{ $t('admin.training.generate_steps_hint', { sets: form.sets, reps: form.reps, total: form.sets * form.reps }) }}</p>
                    <button type="button" class="btn btn-outline-primary btn-sm" @click="generateStepsFromFormula()">{{ $t('admin.training.generate_steps_btn') }}</button>
                </div>

                <p v-if="form.steps.length == 0" class="text-muted">{{ $t('admin.training.no_steps_hint') }}</p>

                <div v-for="(step, index) in form.steps" :key="index" class="card mb-3 step-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>{{ $t('admin.training.step_label_label') }} #{{ index + 1 }} — {{ $t('admin.training.phase_' + step.phase) }}</strong>
                        <button type="button" class="btn btn-danger btn-sm" @click="removeStep(index)">{{ $t('admin.training.remove_step_btn') }}</button>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>{{ $t('admin.training.step_phase_label') }}</label>
                                <select v-model="step.phase" class="form-control">
                                    <option v-for="p in phases" :key="p" :value="p">{{ $t('admin.training.phase_' + p) }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>{{ $t('admin.training.step_label_label') }}</label>
                                <input type="text" v-model="step.label" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label>{{ $t('admin.training.step_duration_label') }}</label>
                                <input type="number" min="0" v-model.number="step.duration_seconds" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ $t('admin.training.step_instructions_label') }}</label>
                            <textarea v-model="step.instructions" class="form-control" rows="2"></textarea>
                        </div>

                        <single_image_add
                            :title_prop="$t('admin.training.step_image_label') + (trainingMode === 'shop_product' ? ' *' : '')"
                            :crop_ratio_prop="{ width: 16, height: 9 }"
                            @update_single_image="step._imageFile = $event"
                        />
                    </div>
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

            <!-- Tab 4: Linked Products -->
            <div class="col-md-12" v-show="tab_num == 4">
                <p class="text-muted">{{ $t('admin.training.linked_products_hint') }}</p>

                <div v-if="selectedProducts.length > 0" class="mb-3">
                    <label class="form-label">{{ $t('admin.training.selected_products_count', { count: selectedProducts.length }) }}</label>
                    <div class="d-flex flex-wrap gap-1">
                        <span v-for="p in selectedProducts" :key="p.id" class="opt-tag">
                            {{ p.title }}
                            <span class="opt-tag-x" @click="removeProduct(p.id)">✕</span>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.product_search_placeholder') }}</label>
                    <input type="text" v-model="productSearchQuery" @input="onProductSearchInput" class="form-control" :placeholder="$t('admin.training.product_search_placeholder')">
                </div>

                <div v-if="productSearchResults.length > 0" class="options-list border rounded">
                    <div v-for="p in productSearchResults" :key="p.id"
                         class="opt-row d-flex align-items-center gap-2 p-2 border-bottom"
                         :class="{ 'opt-selected': isProductSelected(p.id) }"
                         @click="toggleProduct(p)">
                        <input type="checkbox" :checked="isProductSelected(p.id)" @click.stop="toggleProduct(p)">
                        <div class="fw-bold" style="font-size:13px;">{{ p.title }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import single_image_add from '../../items/single_image/singleImageAddComponent.vue'

export default {
    name: 'TrainingAddComponent',
    components: {
        single_image_add,
    },
    data() {
        return {
            tab_num: 1,
            mainImageFile: null,
            is_loading: false,
            types: ['fingerboard', 'campus', 'flexibility', 'strength', 'endurance'],
            difficulties: ['easy', 'medium', 'hard'],
            phases: ['prepare', 'hang', 'rest', 'recover', 'work', 'stretch'],
            trainingMode: 'custom',
            categories: [],
            subcategories: [],
            products: [],
            selectedCategoryId: 0,
            selectedSubcategoryId: 0,
            selectedProducts: [],
            productSearchQuery: '',
            productSearchResults: [],
            productSearchTimeout: null,
            form: {
                name: '',
                description: '',
                type: 'fingerboard',
                difficulty: 'medium',
                target_muscle: '',
                coach_tip: '',
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
        generateStepsFromFormula() {
            const sets = parseInt(this.form.sets) || 0;
            const reps = parseInt(this.form.reps) || 0;
            if (sets < 1 || reps < 1) return;

            if (this.form.steps.length && !confirm(this.$t('admin.training.generate_steps_confirm'))) {
                return;
            }

            const steps = [];
            for (let s = 1; s <= sets; s++) {
                for (let r = 1; r <= reps; r++) {
                    steps.push({
                        phase: 'hang',
                        label: this.$t('admin.training.generated_hang_label', { set: s, rep: r }),
                        duration_seconds: this.form.hang_time,
                        image_url: '', instructions: '', _imageFile: null,
                    });
                    if (!(s === sets && r === reps)) {
                        steps.push({
                            phase: 'rest',
                            label: this.$t('admin.training.generated_rest_label', { set: s }),
                            duration_seconds: this.form.rest_time,
                            image_url: '', instructions: '', _imageFile: null,
                        });
                    }
                }
                if (s < sets) {
                    steps.push({
                        phase: 'recover',
                        label: this.$t('admin.training.generated_recover_label', { set: s + 1 }),
                        duration_seconds: this.form.recover_time,
                        image_url: '', instructions: '', _imageFile: null,
                    });
                }
            }
            this.form.steps = steps;
        },
        onModeChange() {
            if (this.trainingMode === 'custom') {
                this.form.product_id = null;
                this.selectedCategoryId = 0;
                this.selectedSubcategoryId = 0;
                this.subcategories = [];
                this.products = [];
            } else if (!this.categories.length) {
                this.fetchCategories();
            }
        },
        fetchCategories() {
            axios.get('/get_product/get_product_category/get_all_product_category')
                .then(response => {
                    this.categories = response.data || [];
                });
        },
        onCategoryChange() {
            this.selectedSubcategoryId = 0;
            this.products = [];
            this.form.product_id = null;
            this.subcategories = [];
            if (this.selectedCategoryId) {
                axios.get('/get_product/get_product_category/get_subcategory/get_subcategories_for_category/' + this.selectedCategoryId)
                    .then(response => {
                        this.subcategories = response.data || [];
                    });
            }
        },
        onSubcategoryChange() {
            this.products = [];
            this.form.product_id = null;
            if (this.selectedSubcategoryId) {
                axios.get('/set_training/search_products', { params: { subcategory_id: this.selectedSubcategoryId } })
                    .then(response => {
                        this.products = response.data.products || [];
                    });
            }
        },
        onProductSearchInput() {
            clearTimeout(this.productSearchTimeout);
            this.productSearchTimeout = setTimeout(() => {
                axios.get('/set_training/search_products', { params: { query: this.productSearchQuery } })
                    .then(response => {
                        this.productSearchResults = response.data.products || [];
                    });
            }, 300);
        },
        isProductSelected(id) {
            return this.selectedProducts.some(p => p.id === id);
        },
        toggleProduct(p) {
            if (this.isProductSelected(p.id)) {
                this.removeProduct(p.id);
            } else {
                this.selectedProducts.push(p);
            }
        },
        removeProduct(id) {
            const i = this.selectedProducts.findIndex(p => p.id === id);
            if (i !== -1) this.selectedProducts.splice(i, 1);
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
            if (this.mainImageFile) formData.append('image', this.mainImageFile);
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
            this.selectedProducts.forEach(p => formData.append('product_ids[]', p.id));
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

            axios
            .post('/set_training/create_training', this.buildFormData())
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
.mode-warning-box {
    border: 2px solid #ffc107;
}
.step-card {
    border-radius: 8px;
    overflow: hidden;
}
.step-card .card-header {
    background: #f8f9fa;
}
.options-list {
    max-height: 340px;
    overflow-y: auto;
}
.opt-row {
    cursor: pointer;
    transition: background .12s;
}
.opt-row:hover {
    background: #f0f4ff;
}
.opt-selected {
    background: #e8f0fe;
}
.opt-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #0d6efd;
    color: #fff;
    border-radius: 4px;
    padding: 3px 8px;
    font-size: 12px;
}
.opt-tag-x {
    cursor: pointer;
    opacity: .8;
}
.opt-tag-x:hover {
    opacity: 1;
}
</style>
