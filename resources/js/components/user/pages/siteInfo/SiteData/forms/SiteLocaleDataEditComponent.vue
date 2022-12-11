<template>
    <form id="us_form" name="contact-form" class="width_100"  method="POST" @submit.prevent="edit_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Title </label>
            <div class="col-xs-8">
                <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                <input type="text" name="value name" v-model="locale_data.title" class="form-control"> 
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Short description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.short_description" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> text </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.text" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>


        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> ice_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.ice_description" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> mount_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.mount_description" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> outdoor_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.outdoor_description" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> indoor_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.indoor_description" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> topo_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.topo_description" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> what_we_do_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.what_we_do_description" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Shop short description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.shop_short_description" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Shop description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.shop_description" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Service description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.services_description" :config="editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        props: [
            'locale_data_prop',
        ],
        data(){
            return {
                // editor: 'editor',
                editorConfig:{},
                
                locale_data: [

                ],
            }
        },
        mounted() {
            // console.log(this.locale_data_prop);
            this.locale_data = this.locale_data_prop
            // this.$emit('locale_form_data', this.locale_data)
        },

    }
</script>

<style>

</style>