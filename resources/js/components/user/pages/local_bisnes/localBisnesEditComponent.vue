<template>
    <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="edit_bisnes()" >Save update</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        
                        <label for="1" >Global info</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        
                        <label for="2" >English text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >Georgian text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        
                        <label for="4" >Russion text</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="display-4"><span>Service global information</span></h2>
                                <p class="lead">Service global information.</p>
                            </div>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" id="global_form" ref="myForm" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix" v-if="!data.global_data.public_totaly">
                            <label for="name" class='col-xs-2 control-label'> Published befor (After this data it`s whil by not public`) </label>
                            <div class="col-xs-8">
                                <input type="datetime-local" class="form-control" id="datemin" name="datemin" min="2000-01-02" v-model="data.global_data.published_data" >
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Totaly public </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" v-model="data.global_data.public_totaly" >
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="article_id" class="col-xs-2 control-label"> Article </label>
                            <div class="col-xs-8">
                                <select class="form-control" name="article_id" v-model="data.global_data.article_id" >
                                    <option v-for="region in regions" :key="region" v-bind:value="region.id"> {{ region.url_title }} </option>
                                </select>
                            </div>
                        </div>
                    </form>
                    
                    <div class="col-md-12">
                        <div class="row">
                            Olredy added
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-hover" id="dev-table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>|</th>
                                            <th>Delite</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="bisnes_old_image in bisnes_old_images" :key="bisnes_old_image.id">
                                            <td>
                                                <img class="img-responsive" :src="'../../../../images/suport_local_bisnes_img/'+bisnes_old_image.image" :alt="bisnes_old_image.image">
                                            </td>
                                            <td>|</td>
                                            <td>
                                                <button class="btn btn-danger" @click="del_bisnes_image_from_db(bisnes_old_image.id)">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            New images
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-groupe">
                                    <button class="btn btn-primary float-left" @click="add_bisnes_new_image_value()" >Add new bisnes imagee</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-hover" id="dev-table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>|</th>
                                            <th>Delite</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="bisnes_image in bisnes_new_images" :key="bisnes_image.id">
                                            <td>
                                                <form ref="myForm">
                                                    <input type="file" name="image" id="image" v-on:change="onFileChange($event, bisnes_image.id)">
                                                </form> 
                                            </td>
                                            <td>|</td>
                                            <td>
                                                <button class="btn btn-danger" @click="del_bisnes_image(bisnes_image.id)">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-groupe">
                                    <button class="btn btn-primary float-left" @click="add_bisnes_new_image_value()">Add new bisnes imagee</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Service english version</h2>
                            <p class="lead">Service english version for site localisation.</p>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_data.title"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Change URL title </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" @click="change_url_title_in_global_bisnes()">
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.short_description" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.text" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Service rusion version</h2>
                            <p class="lead">Service rusion version for site localisation.</p>
                        </div>
                    </div>
    
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="title" v-model="data.ru_data.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description" v-model="data.ru_data.short_description"  rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ru_data.short_description" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ru_data.text" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 4">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Service georgian version</h2>
                            <p class="lead">Service georgian version for site localisation.</p>
                        </div>
                    </div>
    
                    <form class="width_100" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="value name"  v-model="data.ka_data.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_data.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.short_description" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ka_data.text" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: [
        // 'back_url',
    ],
    data(){
        return {
            tab_num: 1,

            bisnes_new_images: [],
            bisnes_old_images: [],
            regions: [],

            editorConfig: {},

            data: {
                global_data: {},
                us_data: {},
                ka_data: {},
                ru_data: {}
            },

            change_url_title: false
        }
    },
    mounted() {
        this.get_editing_bisnes_data()
        this.get_region_data()
    },
    methods: {
        get_region_data: function () {
            axios
                .get("../../api/article/")
                .then((response) => {
                    this.regions = response.data;
                })
                .catch((error) => console.log(error));
        },

        onFileChange(event, item_id){
            let image = event.target.files[0]
            let id = item_id - 1 
            this.bisnes_new_images[id]['image'] = image
        },

        add_bisnes_new_image_value(){
            if(this.bisnes_old_images){
                if(this.bisnes_new_images.length + this.bisnes_old_images.length < 8){
                    var new_item_id = this.bisnes_new_images.length+1

                    this.bisnes_new_images.push(
                        {
                            id: new_item_id,
                            image: '',
                        }
                    );
                }
            }
            else{
                if(this.bisnes_new_images.length < 8){
                    var new_item_id = this.bisnes_new_images.length+1

                    this.bisnes_new_images.push(
                        {
                            id: new_item_id,
                            image: '',
                        }
                    );
                }
            }
        },

        del_bisnes_image_from_db(image_id){
            if(confirm('Are you sure, you want delite this image?')){
                axios
                .delete("../../../api/bisnes/del_local_bisnes_image/"+image_id)
                .then(response => {
                    this.get_editing_bisnes_data()
                })
                .catch(
                    error => console.log(error)
                );
            }
        },

        get_editing_bisnes_data(){
            this.data_for_tab = []
            axios
            .get("../../api/bisnes/get_editing_local_bisnes_info/"+this.$route.params.id)
            .then(response => {
                this.editing_data = response.data

                this.data = {
                    global_data: response.data.global_bisnes,

                    us_data: response.data.us_bisnes,
                    ru_data: response.data.ru_bisnes,
                    ka_data: response.data.ka_bisnes,
                }

                this.bisnes_old_images = response.data.bisnes_images
            })
            .catch(
                error => console.log(error)
            );

        },

        del_bisnes_image(id){
            this.removeObjectWithId(this.bisnes_new_images, id);
        },

        removeObjectWithId(arr, id) {
            const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
            arr.splice(objWithIdIndex, 1);

            return arr;
        },

        change_url_title_in_global_bisnes(){
            if(!this.change_url_title){
                if(confirm('Are you sure, you want change URL title? It vhile bad for SEO potimization')){
                    this.change_url_title = true
                }
            }
            else{
                this.change_url_title = false 
            }
        },

        edit_bisnes() {
            if (this.change_url_title) {
                this.data.global_data.change_url_title = this.change_url_title
                this.data.global_data.us_title_for_url_title = this.data.us_data.title
            }
            else{
                this.data.global_data.change_url_title = false
                this.data.global_data.us_title_for_url_title = false
            }

            let formData = new FormData();

            var loop_num = 0
            this.bisnes_new_images.forEach(image => {
                formData.append('bisnes_new_images['+loop_num+']', image.image)
                loop_num++
            });
            loop_num = 0

            formData.append('data', JSON.stringify(this.data))

            axios
            .post('../../api/bisnes/edit_local_bisnes/'+this.$route.params.id, 
                formData
            )
            .then(response => {
                this.go_back(true)
            })
            .catch(
                error => console.log(error)
            );
        },

        go_back: function(back_action = false) {
            if(back_action == false){
                if(confirm('Are you sure, you want go back?')){
                    this.$router.go(-1)
                }
            }
            else{
                this.$router.go(-1)
            }
        },
    }
}
</script>