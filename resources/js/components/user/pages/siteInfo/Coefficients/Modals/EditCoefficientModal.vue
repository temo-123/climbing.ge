<template>
    <StackModal
            :show="is_show_modal"
            :title="$t('admin.coefficients.edit_title')"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
            @save="$refs.edit_coefficient_form.requestSubmit()"
            @close="close_modal"
        >
        <validator_alerts_component
            :errors_prop="error"
        />

        <form ref="edit_coefficient_form" id="edit_coefficient_form" v-on:submit.prevent="edit_coefficient">
            <div class="form-group">
                <input type="text" class="form-control" v-model.trim="data.slug" name="slug" id="edit_coefficient_slug" pattern="[a-z0-9_]+" maxlength="100" :placeholder="$t('admin.coefficients.slug_placeholder')" required>
                <small class="form-text text-muted">{{ $t('admin.coefficients.slug_hint') }}</small>
            </div>
            <div class="form-group">
                <input type="number" step="any" class="form-control" v-model="data.value" name="value" id="edit_coefficient_value" :placeholder="$t('admin.coefficients.value_placeholder')" required>
            </div>
            <div class="form-group">
                <textarea class="form-control" v-model="data.description" name="description" id="edit_coefficient_description" rows="3" maxlength="1000" :placeholder="$t('admin.coefficients.description_placeholder')"></textarea>
            </div>
        </form>
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
                current_item: null,

                error: [],

                is_show_modal: false,
            }
        },

        methods: {
            edit_coefficient(){
                axios
                .post('set_coefficient/update/' + this.current_item.id, this.data)
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
                this.current_item = null
                this.error = []
            },
            show_modal_with_data(item){
                this.current_item = item
                this.data.slug = item.slug || ''
                this.data.value = item.value ?? ''
                this.data.description = item.description || ''
                this.is_show_modal = true
            }
        }
    }
</script>
