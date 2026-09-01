<template>
    <div class="tabs">

        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="button" class="btn btn-primary" @click="go_back()">{{ $t('common.back') }}</button>
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="button" class="btn btn-primary" @click="edit_organization()">{{ $t('common.save') }}</button>
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
                <div class="form-group">
                    <label>{{ $t('common.name') }} <span class="text-danger">*</span></label>
                    <input type="text" v-model="data.name" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.partner_organizations.discount_label') }} <span class="text-danger">*</span></label>
                    <input type="number" min="0" max="100" step="0.01" v-model.number="data.discount" class="form-control">
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            data: {
                name: '',
                discount: 0,
            },
            errors: {},
            is_loading: true,
            is_back_action: true,
        }
    },
    mounted() {
        document.querySelector('body').style.marginLeft = '0';
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        this.get_editing_data()
    },
    beforeRouteLeave(to, from, next) {
        if (this.is_back_action == true) {
            if (window.confirm(this.$t('common.confirm_leave_unsaved'))) {
                this.is_back_action = false
                next()
            } else {
                next(false)
            }
        } else {
            next()
        }
    },
    methods: {
        get_editing_data() {
            axios.get('/set_partner_organization/get_editing_organization_data/' + this.$route.params.id)
                .then(response => {
                    this.data = response.data
                })
                .finally(() => this.is_loading = false)
        },

        edit_organization() {
            this.errors = {}
            this.is_loading = true

            axios.post('/set_partner_organization/edit_organization/' + this.$route.params.id, { data: this.data })
                .then(response => {
                    this.go_back(true)
                })
                .catch(err => {
                    if (err.response && err.response.status == 422) {
                        this.errors = err.response.data.validation
                    }
                })
                .finally(() => this.is_loading = false)
        },

        go_back(action = false) {
            this.is_back_action = this.$going.back(this, action)
        },
    }
}
</script>

<style scoped>
.tabs {
    flex-wrap: wrap;
}
</style>
