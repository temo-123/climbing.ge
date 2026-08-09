<template>
    <div class="tabs">

        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="button" class="btn btn-primary" @click="$router.push({name: 'trainingPlansList'})">{{ $t('common.back') }}</button>
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
                        <label for="tab2">{{ $t('admin.training.sessions_title') }}</label>
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

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ $t('admin.training.emoji_label') }}</label>
                        <input type="text" v-model="form.emoji" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ $t('admin.training.level_label') }} <span class="text-danger">*</span></label>
                        <select v-model="form.level" class="form-control">
                            <option v-for="l in levels" :key="l" :value="l">{{ $t('admin.training.level_' + l) }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.tagline_label') }}</label>
                    <input type="text" v-model="form.tagline" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ $t('common.description') }}</label>
                    <textarea v-model="form.description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.coach_note_label') }}</label>
                    <textarea v-model="form.coach_note" class="form-control" rows="2"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ $t('admin.training.days_per_week_label') }} <span class="text-danger">*</span></label>
                        <input type="number" min="1" max="7" v-model.number="form.days_per_week" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ $t('admin.training.weeks_label') }}</label>
                        <input type="number" min="0" v-model.number="form.weeks" class="form-control">
                    </div>
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

            <!-- Tab 2: Sessions -->
            <div class="col-md-12" v-show="tab_num == 2">
                <p v-if="availableTrainings.length == 0" class="text-muted">{{ $t('admin.training.no_trainings_available_hint') }}</p>

                <div v-for="(session, index) in form.sessions" :key="index" class="card mb-3 p-3">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>{{ $t('admin.training.day_index_label') }}</label>
                            <input type="number" min="0" max="6" v-model.number="session.day_index" class="form-control">
                        </div>
                        <div class="form-group col-md-8">
                            <label>{{ $t('admin.training.day_label_label') }}</label>
                            <input type="text" v-model="session.day_label" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ $t('admin.training.session_trainings_label') }}</label>
                        <select multiple v-model="session.training_ids" class="form-control" style="height: 150px;">
                            <option v-for="training in availableTrainings" :key="training.id" :value="training.id">{{ training.name }}</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" @click="removeSession(index)">{{ $t('admin.training.remove_session_btn') }}</button>
                </div>

                <button type="button" class="btn btn-secondary" @click="addSession()">{{ $t('admin.training.add_session_btn') }}</button>
            </div>

            <!-- Tab 3: Georgian translation -->
            <div class="col-md-12" v-show="tab_num == 3">
                <p class="text-muted">{{ $t('admin.training.ka_translation_hint') }}</p>

                <div class="form-group">
                    <label>{{ $t('admin.training.name_label') }} (KA)</label>
                    <input type="text" v-model="form.translations.ka.name" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.tagline_label') }} (KA)</label>
                    <input type="text" v-model="form.translations.ka.tagline" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ $t('common.description') }} (KA)</label>
                    <textarea v-model="form.translations.ka.description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.training.coach_note_label') }} (KA)</label>
                    <textarea v-model="form.translations.ka.coach_note" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'TrainingPlanAddComponent',
    data() {
        return {
            tab_num: 1,
            is_loading: false,
            levels: ['beginner', 'intermediate', 'expert', 'maintenance'],
            availableTrainings: [],
            form: {
                name: '',
                emoji: '',
                level: 'beginner',
                tagline: '',
                description: '',
                coach_note: '',
                days_per_week: 3,
                weeks: 0,
                is_published: 1,
                sessions: [],
                translations: {
                    ka: { name: '', tagline: '', description: '', coach_note: '' },
                },
            },
            errors: {},
        }
    },
    mounted() {
        document.querySelector('body').style.marginLeft = '0';
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        this.load_trainings();
    },
    methods: {
        load_trainings() {
            axios
            .get('/set_training/get_all_trainings')
            .then(response => this.availableTrainings = response.data)
            .catch(error => console.log(error));
        },
        addSession() {
            this.form.sessions.push({ day_index: this.form.sessions.length % 7, day_label: '', training_ids: [] });
        },
        removeSession(index) {
            this.form.sessions.splice(index, 1);
        },
        save() {
            this.is_loading = true;
            this.errors = {};

            axios
            .post('/set_training_plan/create_plan', this.form)
            .then(() => {
                this.$router.push({ name: 'trainingPlansList' });
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
