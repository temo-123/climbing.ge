<template>
    <div class="row">
        <div class="col-sm-3">
            <left-menu />
        </div>
        <div class="col-sm-9">
            <div class="panel panel-default target">
                <div class="panel-body">

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col" >
                                <input type="radio" id="1" :value="1" v-model="tab_num">
                                
                                <label for="1" >Gallery images</label>
                            </div>
                            <div class="col" >
                                <input type="radio" id="2" :value="2" v-model="tab_num">
                                
                                <label for="2" >Images category</label>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-if="tab_num == 1">
                        <div class="col-md-12" >
                            <div class="row">
                                <galleryComponent/>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-if="tab_num == 2">
                        <div class="col-md-12" >
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="add_buttom float-left">
                                            <span>
                                                <span>
                                                    <button class="btn btn-primary pull-left">New gallery image category</button>
                                                </span>
                                            </span>
                                        </div>

                                        <div class="form-groupe float-right">
                                            <button class="btn main-btn" >refresh</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <table class="table table-hover" id="dev-table">
                                    <thead>
                                        <tr>
                                            <th style='text-align: center;'>
                                                <input type="checkbox" class="all">
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
                                        <tr v-for="table_info in categories" :key="table_info.id">
                                            <td style='text-align: center;'>
                                                <input type="checkbox">
                                            </td>
                                            <td>|</td>
                                            <td>{{table_info.id}}</td>
                                            
                                            <td>|</td>
                                            <td>{{table_info.id}}</td>
                                            
                                            <td>|</td>
                                            <td>edit</td>
                                            
                                            <td>|</td>
                                            <td>del</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import galleryComponent from '../items/GalleryComponent.vue';
    export default {
        data(){
            return {
                categories: [],
                tab_num: 1
            }
        },
        components: {
            galleryComponent,
        },
        mounted() {
            this.get_images_categories()
        },
        watch: {
            '$route' (to, from) {
                this.categories = [],
                this.get_images_categories()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_images_categories(){
                axios
                .get("../api/gallery_image_category/")
                .then(response => {
                    this.categories = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>