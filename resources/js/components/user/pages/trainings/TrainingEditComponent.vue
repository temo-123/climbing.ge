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
                            <label>{{ $t('admin.training.image_url_label') }}</label>
                            <input type="text" v-model="step.image_url" class="form-control">
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
    name: 'TrainingEditComponent',
    data() {
        return {
            tab_num: 1,
            is_loading: false,
            types: ['fingerboard', 'campus', 'flexibility', 'strength', 'endurance'],
            difficulties: ['easy', 'medium', 'hard'],
            phases: ['prepare', 'hang', 'rest', 'recover', 'work', 'stretch'],
            form: {
                name: '',
                description: '',
                type: 'fingerboard',
                difficulty: 'medium',
                target_muscle: '',
                coach_tip: '',
                image_url: '',
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
        this.load();
    },
    methods: {
        load() {
            this.is_loading = true;
            axios
            .get('/set_training/get_training_data/' + this.$route.params.id)
            .then(response => {
                const data = response.data;
                this.form.name = data.name;
                this.form.description = data.description;
                this.form.type = data.type;
                this.form.difficulty = data.difficulty;
                this.form.target_muscle = data.target_muscle;
                this.form.coach_tip = data.coach_tip;
                this.form.image_url = data.image_url;
                this.form.hang_time = data.hang_time;
                this.form.rest_time = data.rest_time;
                this.form.reps = data.reps;
                this.form.sets = data.sets;
                this.form.recover_time = data.recover_time;
                this.form.is_published = data.is_published ? 1 : 0;
                this.form.steps = (data.steps || []).map(step => ({
                    phase: step.phase,
                    label: step.label,
                    duration_seconds: step.duration_seconds,
                    image_url: step.image_url,
                    instructions: step.instructions,
                }));

                const kaTranslation = (data.translations || []).find(t => t.locale === 'ka');
                if (kaTranslation) {
                    this.form.translations.ka = {
                        name: kaTranslation.name,
                        description: kaTranslation.description,
                        coach_tip: kaTranslation.coach_tip,
                        target_muscle: kaTranslation.target_muscle,
                    };
                }
            })
            .catch(error => console.log(error))
            .finally(() => this.is_loading = false);
        },
        addStep() {
            this.form.steps.push({ phase: 'work', label: '', duration_seconds: 30, image_url: '', instructions: '' });
        },
        removeStep(index) {
            this.form.steps.splice(index, 1);
        },
        save() {
            this.is_loading = true;
            this.errors = {};

            axios
            .post('/set_training/update_training/' + this.$route.params.id, this.form)
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
