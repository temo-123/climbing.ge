<template>
    <div class="col-md-12">
        <div class="jumbotron width_100">
            <div class="container">
                <h2 class="display-4"><span style="text-transform: capitalize"> {{ this.title }} </span></h2>
                <p class="lead">{{ this.description }}</p>
            </div>
        </div>

        <form method="POST" @submit.prevent="add_article">
            <div class="form-group clearfix row">
                <label for="name" class='col-md-2 control-label'> {{ $t('common.title') }} </label>
                <div class="col-md-10">
                    <input type="text" name="name" v-model="data.title"  class="form-control">
                    <div class="alert alert-danger" role="alert" v-if="errors.title">
                        {{ errors.title[0] }}
                    </div>
                </div>
            </div>

            <div class="form-group clearfix row">
                <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.locale_form.short_description') }} </label>
                <div class="col-md-10">
                    <small_editor v-model="data.short_description" />
                    <div class="alert alert-danger" role="alert" v-if="errors.short_description">
                        {{ errors.short_description[0] }}
                    </div>
                </div>
            </div>

            <div class="form-group clearfix row">
                <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.locale_form.text_label') }} </label>
                <div class="col-md-10">

                    <big_editor v-model="data.text" />
                </div>
            </div>

        </form>
    </div>
</template>

<script>
    // import GlobalInfoFormBlock from '../../../../items/GlobalInfoFormBlockComponent.vue'

    export default {
        components: {
            // GlobalInfoFormBlock,
        },
        mixins: [
            // editor_config
        ],
        props: [
            'locale_prop',

            'title',
            'description'
        ],
        data(){
            return {
                errors: [],
                
                editor_config: {
                    short_description_text: {},
                    text: {},
                },

                data: {
                    title: '',
                    short_description: '',
                    text: '',
                },
            }
        },
        mounted() {
            this.$emit('locale_form_data', this.data)
        },
        watch: {
            // global_block: function(){
            //     this.global_blocks = this.global_blocks_prop
            // },
        },
        methods: {
            // 
        }
    }
</script>


<style>

</style>