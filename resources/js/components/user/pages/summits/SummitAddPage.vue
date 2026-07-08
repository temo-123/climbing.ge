<template>
    <div class="tabs">

        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="button" class="btn btn-primary" @click="$router.push('/summits')">{{ $t('common.back') }}</button>
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
                        <label for="tab2">{{ $t('common.english_text') }}</label>
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
                    <label>{{ $t('admin.summits.height_meters_label') }}</label>
                    <input type="number" v-model="form.height" class="form-control" :placeholder="$t('admin.summits.height_placeholder')" min="0" />
                </div>

                <div class="form-group">
                    <label>{{ $t('admin.summits.mount_massive_label') }}</label>
                    <select v-model="form.mount_id" class="form-control">
                        <option :value="null">{{ $t('admin.summits.no_mount_massive_option') }}</option>
                        <option v-for="mount in mounts" :key="mount.id" :value="mount.id">{{ mount.name }}</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ $t('admin.summits.latitude_label') }}</label>
                        <input type="number" v-model="form.latitude" class="form-control" step="any" :placeholder="$t('admin.summits.lat_placeholder')" />
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ $t('admin.summits.longitude_label') }}</label>
                        <input type="number" v-model="form.longitude" class="form-control" step="any" :placeholder="$t('admin.summits.lng_placeholder')" />
                    </div>
                </div>

                <div class="form-group">
                    <single_image_add
                        :title_prop="$t('admin.summits.summit_image_title')"
                        @update_single_image="form.image = $event"
                    />
                </div>

                <div class="form-group">
                    <published_item
                        :published_prop="form.published"
                        @item_data="form.published = $event"
                    />
                </div>

            </div>

            <!-- Tab 2: English text -->
            <div class="col-md-12" v-show="tab_num == 2">

                <div class="form-group">
                    <label>{{ $t('common.title') }} <span class="text-danger">*</span></label>
                    <input type="text" v-model="form.title" class="form-control" :placeholder="$t('admin.summits.summit_title_placeholder')" />
                </div>

                <div class="form-group">
                    <label>{{ $t('common.description') }}</label>
                    <textarea v-model="form.description" class="form-control" rows="4" :placeholder="$t('admin.summits.description_optional_placeholder')"></textarea>
                </div>

            </div>

            <!-- Tab 3: Georgian text -->
            <div class="col-md-12" v-show="tab_num == 3">

                <div class="form-group">
                    <label>{{ $t('common.title') }} (KA)</label>
                    <input type="text" v-model="form.ka_title" class="form-control" :placeholder="$t('admin.summits.ka_title_placeholder')" />
                </div>

                <div class="form-group">
                    <label>{{ $t('common.description') }} (KA)</label>
                    <textarea v-model="form.ka_description" class="form-control" rows="4" :placeholder="$t('admin.summits.ka_description_placeholder')"></textarea>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
import breadcrumb from '../../items/BreadcrumbComponent.vue'
import single_image_add from '../../items/single_image/singleImageAddComponent.vue'
import published_item from '../../items/form/parts/PublishedValueComponent.vue'

export default {
    name: 'SummitAddPage',
    components: { breadcrumb, single_image_add, published_item },
    data() {
        return {
            tab_num: 1,
            is_loading: false,
            form: {
                title: '',
                ka_title: '',
                description: '',
                ka_description: '',
                height: null,
                latitude: null,
                longitude: null,
                mount_id: null,
                published: 0,
                image: null,
            },
            mounts: [],
            errors: {},
        }
    },
    mounted() {
        document.querySelector('body').style.marginLeft = '0'
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0'
        this.load_mounts()
    },
    methods: {
        load_mounts() {
            axios.get('get_summit_admin/get_mounts_list')
                .then(response => { this.mounts = response.data })
                .catch(() => {})
        },
        save() {
            this.is_loading = true
            this.errors = {}

            const formData = new FormData()
            formData.append('title', this.form.title)
            formData.append('ka_title', this.form.ka_title ?? '')
            formData.append('description', this.form.description ?? '')
            formData.append('ka_description', this.form.ka_description ?? '')
            formData.append('height', this.form.height ?? '')
            formData.append('latitude', this.form.latitude ?? '')
            formData.append('longitude', this.form.longitude ?? '')
            formData.append('mount_id', this.form.mount_id ?? '')
            formData.append('published', this.form.published)
            if (this.form.image) formData.append('image', this.form.image)

            axios.post('set_summit/store', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(() => { this.$router.push('/summits') })
                .catch(error => {
                    if (error.response?.data?.errors) {
                        this.errors = error.response.data.errors
                    }
                })
                .finally(() => { this.is_loading = false })
        }
    }
}
</script>

<style scoped>
.tabs {
    flex-wrap: wrap;
}
.tabs label {
    order: 1;
    display: block;
    padding: 1rem 2rem;
    margin-right: 0.2rem;
    cursor: pointer;
    background: #ccced0;
    font-weight: bold;
    transition: background ease 0.2s;
}
.tabs .tab {
    order: 99;
    flex-grow: 1;
    width: 100%;
    display: none;
    padding: 1rem;
    background: #fff;
}
.tabs input[type="radio"] {
    display: none;
}
.tabs input[type="radio"]:checked + label {
    background: #fff;
    border: 1px solid #ccc !important;
}
.tabs input[type="radio"]:checked + label + .tab {
    display: block;
}
@media (max-width: 45em) {
    .tabs .tab,
    .tabs label {
        order: initial;
    }
    .tabs label {
        width: 100%;
        margin-right: 0;
        margin-top: 0.2rem;
    }
}
</style>
