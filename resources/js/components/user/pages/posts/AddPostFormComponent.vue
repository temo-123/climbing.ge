<template>
    <div class="tabs">
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="save()" >Save</button>
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <validator_alerts_component
                :errors_prop="error"
            />
        </div>
        <div class="row" v-show="!is_loading">
            <div class="col-md-12">
                <div class="row">
                    <GlobalDataForm 
                        @global_form_data="data.global_post = $event" 
                    />
                    <PostImage
                        ref="PostImage" @upload_img="upload_post_image"
                    />
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        
                        <label for="1" >English text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        
                        <label for="2" >Georgian text</label>
                    </div>
                </div>
            </div>
            <div v-show="tab_num == 1">
                <LocaleDataForm 
                    @locale_form_data="data.us_post = $event"

                    :title="$t('Post english text')"
                    :description="$t('Post english description')"
                />
            </div>
            <div v-show="tab_num == 2">
                <LocaleDataForm 
                    @locale_form_data="data.ka_post = $event"

                    :title="$t('Post georgian text')"
                    :description="$t('Post georgian description')"
                />
            </div>
        </div>
    </div>
</template>

<script>
    import GlobalDataForm from './forms/add_forms/GlobalDataFormComponent.vue'
    import LocaleDataForm from './forms/add_forms/LocaleDataFormComponent.vue'
    import PostImage from './forms/add_forms/PostImageFormComponent.vue'

    import validator_alerts_component from '../../items/validator_alerts_component.vue'
    export default {
        components: {
            validator_alerts_component,

            GlobalDataForm,
            LocaleDataForm,
            PostImage,
        },
        props: [
            // 'back_url',
        ],
        data(){
            return {
                tab_num: 1,
                category: this.$route.params.post_category,

                error: [],

                is_back_action_query: true,
                is_loading: false,

                data: {
                    global_post: [],
                    us_post: [],
                    ka_post: [],
                },

                post_image: [],
            }
        },
        mounted() {
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action_query == true){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.is_back_action_query = false;
                    next()
                } else {
                    next(false)
                }
            }else {
                next()
            }
        },
        methods: {
            save(){
                this.is_loading = true

                this.error = []

                let formData = new FormData();
                formData.append('image', this.post_image);
                formData.append('data', JSON.stringify(this.data))
                formData.append('global_blocks', JSON.stringify(this.global_blocks))
                
                axios
                .post('/post/add_post/', 
                    formData,
                )
                .then(response => {
                    // if(confirm('Do you want send notification about new post?')){
                    //     this.sand_notification()
                    // }
                    // else{
                        this.go_back(true)
                    // }
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                    else{
                        // alert(error)
                    }
                })
                .finally(() => this.is_loading = false);
            },

            upload_post_image(event){
                this.post_image = event
            },

            // sand_notification() {
            //     this.is_loading = true

            //     axios
            //     .post('/user/notifications/send_post_adding_notification',{
            //         notification_category: this.category
            //     } )
            //     .then(response => {
            //         this.go_back(true)
            //     })
            //     .catch(err => {
            //         console.log(err);
            //     })
            //     .finally(() => this.is_loading = false);
            // },
            
            go_back: function(action = false) {
                this.is_back_action_query = this.$going.back(this, action)
            },
        }
    }
</script>

<style scoped>
    .tabs {
        /* display: flex; */
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
        /* border: 1px solid #ccc!important; */
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