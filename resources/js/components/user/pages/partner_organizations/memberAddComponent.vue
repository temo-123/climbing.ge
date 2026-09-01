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
                <button type="button" class="btn btn-primary" @click="add_member()">{{ $t('common.save') }}</button>
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
                    <label>{{ $t('admin.partner_organizations.organization_col') }} <span class="text-danger">*</span></label>
                    <select v-model="data.organization_id" class="form-control">
                        <option :value="null" disabled>{{ $t('admin.partner_organizations.select_organization') }}</option>
                        <option v-for="organization in organizations" :key="organization.id" :value="organization.id">{{ organization.name }}</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ $t('common.name') }} <span class="text-danger">*</span></label>
                        <input type="text" v-model="data.name" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ $t('common.surname') }} <span class="text-danger">*</span></label>
                        <input type="text" v-model="data.surname" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ $t('common.email') }} <span class="text-danger">*</span></label>
                        <input type="email" v-model="data.email" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ $t('common.phone_number') }}</label>
                        <input type="text" v-model="data.phone_number" class="form-control">
                    </div>
                </div>

                <small class="text-muted d-block mb-3">{{ $t('admin.partner_organizations.auto_link_hint') }}</small>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            data: {
                organization_id: null,
                name: '',
                surname: '',
                email: '',
                phone_number: '',
            },
            organizations: [],
            errors: {},
            is_loading: false,
            is_back_action: true,
        }
    },
    mounted() {
        document.querySelector('body').style.marginLeft = '0';
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        this.get_organizations()
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
        get_organizations() {
            axios.get('/set_partner_organization/get_all_organizations')
                .then(response => {
                    this.organizations = response.data
                })
        },

        add_member() {
            this.errors = {}
            this.is_loading = true

            axios.post('/set_partner_organization_member/add_member', { data: this.data })
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
