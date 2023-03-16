<template>
    <div class="tabs">
        <div class="col-md-12">
            <div class="row">
                <div class="col">
                    <input type="radio" id="1" :value="1" v-model="tab_num" />

                    <label for="1">Gallery images</label>
                </div>
                <div class="col">
                    <input type="radio" id="2" :value="2" v-model="tab_num" />

                    <label for="2">Images category</label>
                </div>
            </div>
        </div>

        <div class="row" v-if="tab_num == 1">
            <div class="col-md-12">
                <!-- <div class="row"> -->
                <galleryComponent 
                    :categories = categories
                />
                <!-- </div> -->
            </div>
        </div>

        <div class="row" v-if="tab_num == 2">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-groupe">
                            <button class="btn btn-primary" @click="open_category_add_modal" >Add image category</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-groupe">
                            <button @click="get_gallery_categories" class="btn btn-success float-right" v-if="!is_user_site_refresh">Refresh ({{user_site_reset_id}})</button>
                            <span class="badge badge-primare mb-1 float-right" v-if="is_user_site_refresh">Updating...</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover" id="dev-table">
                            <thead>
                                <tr>
                                    <th style="text-align: center">
                                        <input type="checkbox" class="all" />
                                    </th>
                                    <th>|</th>
                                    <th>ID</th>

                                    <th>|</th>
                                    <th>Name</th>

                                    <th>|</th>
                                    <th>Edit</th>

                                    <th>|</th>
                                    <th>Delite</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="table_info in categories"
                                    :key="table_info.id"
                                >
                                    <td style="text-align: center">
                                        <input type="checkbox" />
                                    </td>
                                    <td>|</td>
                                    <td>{{ table_info.id }}</td>

                                    <td>|</td>
                                    <td>{{ table_info.us_name }}</td>

                                    <td>|</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" @click="open_category_edit_modal(table_info.id)">Edit</button>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="button" class="btn btn-danger" @click="del_gallery_category(table_info.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <stack-modal
                :show="is_category_add_modal"
                :title="'Add new gallery category'"
                @close="is_category_add_modal = false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form class="form" method="POST" id="add_gallery_category_form" v-on:submit.prevent="add_gallery_category">
                    <input type="text" class="form-control" v-model="form_data.us_name" name="English name" id="English name" placeholder="Enter English name" title="Enter English name">
                    <input type="text" class="form-control" v-model="form_data.ka_name" name="Georgian name" id="Georgian name" placeholder="Enter Georgian name" title="Enter Georgian name">
                    <input type="text" class="form-control" v-model="form_data.ru_name" name="Russian name" id="Russian name" placeholder="Enter Russian name" title="Enter Russian name">   
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="add_gallery_category_form"
                    >
                    Add new
                    </button>
                </div>
            </div>
        </stack-modal>


        <stack-modal
                :show="is_category_edit_modal"
                :title="'Edit gallery category'"
                @close="close_category_edit_modal()"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form class="form" method="POST" id="edit_gallery_category_form" v-on:submit.prevent="edit_gallery_category">
                    <input type="text" class="form-control" v-model="form_data.us_name" name="English name" id="English name" placeholder="Enter English name" title="Enter English name">
                    <input type="text" class="form-control" v-model="form_data.ka_name" name="Georgian name" id="Georgian name" placeholder="Enter Georgian name" title="Enter Georgian name">
                    <input type="text" class="form-control" v-model="form_data.ru_name" name="Russian name" id="Russian name" placeholder="Enter Russian name" title="Enter Russian name">   
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        @click="edit_gallery_category(table_info.id)"
                        form="edit_gallery_category_form"
                    >
                    Update
                    </button>
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import galleryComponent from "../GalleryComponent.vue";

    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
            galleryComponent,
        },
    data() {
        return {
            categories: [],
            tab_num: 1,
            is_category_add_modal: false,
            is_category_edit_modal: false,
            is_user_site_refresh: false,
            user_site_reset_id: 0,
            form_data: {
                us_name: '',
                ka_name: '',
                ru_name: ''
            },
            action_title: '',
            editing_category_id: 0
        };
    },
    mounted() {
        this.get_gallery_categories();
    },
    watch: {
        $route(to, from) {
            (this.categories = []), this.get_gallery_categories();
            window.scrollTo(0, 0);
        },
    },
    methods: {
        close_category_edit_modal(){
            this.is_category_edit_modal = false

            this.form_data = {
                us_name: '',
                ka_name: '',
                ru_name: ''
            }
        },
        open_category_add_modal(){
            this.is_category_add_modal = true
        },
        open_category_edit_modal(id){
            this.is_category_edit_modal = true
            this.get_action_category(id)
        },

        get_action_category(category_id){
            axios
            .get("../api/gallery_image_category/"+category_id)
            .then(response => {
                this.form_data = {
                    us_name: response.data.us_name,
                    ka_name: response.data.ka_name,
                    ru_name: response.data.ru_name,
                }
                this.editing_category_id = category_id
            })
            .catch(
                error => console.log(error)
            );
        },

        add_gallery_category(){
            axios
            .post('../../api/gallery_image_category/', {
                data: this.form_data,
                _method: 'POST'
            })
            .then(response => {
                this.is_category_add_modal = false
                this.get_gallery_categories();

                this.form_data = {
                    us_name: '',
                    ka_name: '',
                    ru_name: ''
                }
            })
            .catch(
                //
            );
        },

        edit_gallery_category(category_id){
            axios
            .post('../../../api/gallery_image_category/' + this.editing_category_id, {        
                editing_data: this.form_data,
                _method: 'PATCH'
            })
            .then(response => {
                this.is_category_edit_modal = false

                this.form_data = {
                    us_name: '',
                    ka_name: '',
                    ru_name: ''
                }

                this.editing_category_id = 0

                this.get_gallery_categories();
            })
            .catch(
                error => console.log(error)
            );
        },

        get_gallery_categories() {
            this.is_user_site_refresh = true
            axios
                .get("../api/gallery_image_category/")
                .then((response) => {
                    this.categories = response.data;
                    this.user_site_reset_id++
                    this.is_user_site_refresh = false
                })
                .catch((error) => console.log(error));
        },
        del_gallery_category(id){
            if(confirm('Are you sure, you want delite it?')){
                axios
                .delete('../api/gallery_image_category/'+id)
                .then(Response => {
                    this.get_gallery_categories()
                })
                .catch(error => console.log(error))
            }
        },
    },
};
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

.tabs input[type="radio"]:checked + label[data-v-5add7a37] {
    background: #fff;
    border: 1px solid #ccc !important;
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
