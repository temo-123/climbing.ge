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
            <div class="form-group col-md-12">
                <notify_subscribers :notify_mode_prop="notify_mode" @item_data="notify_mode = $event" />
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="button" class="btn btn-primary" @click="edit_post">{{ $t('common.save') }}</button>
            </div>
        </div>
        <div class="row" v-show="!is_loading" v-if="error.length != 0">
            <validator_alerts_component :errors_prop="error" />
        </div>

        <div class="row" v-show="!is_loading">

            <div class="col-md-12">
                <div class="row">
                    <GlobalDataForm 
                        :global_data_prop="editing_data.global_post"
                        :category_prop="category"
                        @update:global_data_prop="update_global_post"
                    />
                    
                    <single_image_edit
                        :title_prop="$t('admin.posts.post_image_title')"
                        :existing_image_url_prop="post_old_image ? '/public/images/blog_img/'+post_old_image : ''"
                        :crop_ratio_prop="{ width: 16, height: 9 }"
                        @update_single_image="post_image = $event"
                    />
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        <label for="1" >{{ $t('common.english_text') }}</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        <label for="2" >{{ $t('common.georgian_text') }}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12" v-show="tab_num == 1">
                <LocaleDataForm 
                    :locale_data_prop="editing_data.us_post"
                    :category_prop="category"
                    :locale_prop="'us'"
                    @update:locale_data_prop="data => update_locale_post('us_post', data)"
                />
            </div>
            <div class="col-md-12" v-show="tab_num == 2">
                <LocaleDataForm 
                    :locale_data_prop="editing_data.ka_post"
                    :category_prop="category"
                    :locale_prop="'ka'"
                    @update:locale_data_prop="data => update_locale_post('ka_post', data)"
                />
            </div>
        </div>
    </div>
</template>

<script>
import GlobalDataForm from './forms/edit_forms/GlobalDataFormComponent.vue'
import LocaleDataForm from './forms/edit_forms/LocaleDataFormComponent.vue'
import single_image_edit from '../../items/single_image/singleImageEditComponent.vue'
import notify_subscribers from '../../items/form/parts/NotifySubscribersComponent.vue'
// import validator_alerts_component from '../../items/validator_alerts_component.vue'

export default {
    components: {
        single_image_edit,
        GlobalDataForm,
        LocaleDataForm,
        notify_subscribers,
        // validator_alerts_component
    },
    data() {
        return {
            post_id: this.$route.params.id,
            tab_num: 1,
            error: [],
            post_old_image: '',
            post_image: null,
            notify_mode: 'none',
            editing_data: {
                global_post: {},
                us_post: {},
                ka_post: {}
            },
            category: '',
            is_loading: false,
            is_back_action_query: true,
        }
    },
    mounted() {
        this.get_editing_data();
        document.querySelector('body').style.marginLeft = '0';
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },
    methods: {
        get_editing_data() {
            this.is_loading = true;
            axios
            .get('/set_post/get_editing_post/' + this.post_id)
            .then(response => {
                this.editing_data = response.data;
                this.category = response.data.global_post.category;
                this.post_old_image = this.editing_data.global_post.image
            })
            .catch(() => {})
            .finally(() => this.is_loading = false);
        },

        update_gallery_images(images){
            this.post_gallery_image = images
        },

        edit_post() {
            this.is_loading = true
            this.errors = []

            let formData = new FormData();

            // formData.append('gallery_images', this.post_gallery_image);
            formData.append('image', this.post_image);
            formData.append('data', JSON.stringify(this.editing_data))
            // formData.append('global_blocks', JSON.stringify(this.global_blocks))
            formData.append('notify_mode', this.notify_mode)

            axios
            .post('/set_post/edit_post/' + this.post_id, 
                formData,
            )
            .then(response => {
                this.go_back(true)
            })
            .catch(error => {
                if (error.response && error.response.status == 422) {
                    this.error = error.response.data.validation;
                }
            })
            .finally(() => this.is_loading = false);
        },
        go_back(action = false) {
            this.is_back_action_query = this.$going.back(this, action);
        },
        update_global_post(data) {
            this.editing_data.global_post = data;
        },
        update_locale_post(key, data) {
            this.editing_data[key] = data;
        },
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