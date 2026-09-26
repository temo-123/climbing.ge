<template>
    <StackModal
            :show="is_show_modal"
            :title="$t('admin.coefficients.add_title')"
            @close="close_modal"
            @save="$refs.add_coefficient_form.requestSubmit()"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <validator_alerts_component
                :errors_prop="error"
            />

            <form ref="add_coefficient_form" id="add_coefficient_form" v-on:submit.prevent="add_coefficient">
                <div class="form-group">
                    <input type="text" class="form-control" v-model.trim="data.slug" name="slug" id="coefficient_slug" pattern="[a-z0-9_]+" maxlength="100" :placeholder="$t('admin.coefficients.slug_placeholder')" required>
                    <small class="form-text text-muted">{{ $t('admin.coefficients.slug_hint') }}</small>
                </div>
                <div class="form-group">
                    <input type="number" step="any" class="form-control" v-model="data.value" name="value" id="coefficient_value" :placeholder="$t('admin.coefficients.value_placeholder')" required>
                </div>
                <div class="form-group">
                    <textarea class="form-control" v-model="data.description" name="description" id="coefficient_description" rows="3" maxlength="1000" :placeholder="$t('admin.coefficients.description_placeholder')"></textarea>
                </div>
            </form>
        </div>
    </StackModal>
</template>


<script>
    export default {
        emits: ['update'],

        data() {
            return {
                data: {
                    slug: '',
                    value: '',
                    description: ''
                },

                error: [],
                is_show_modal: false,
            }
        },
        methods: {
            add_coefficient(){
                axios
                .post('set_coefficient/create', this.data)
                .then(response => {
                    this.$emit('update')
                    this.close_modal()
                })
                .catch(err => {
                    if (err.response && err.response.status == 422) {
                        this.error = err.response.data.errors
                    } else {
                        console.log(err);
                    }
                })
            },
            close_modal(){
                this.is_show_modal = false
                this.data = {
                    slug: '',
                    value: '',
                    description: ''
                }
                this.error = []
            },
            show_modal(){
                this.is_show_modal = true
            }
        }
    }
</script>
