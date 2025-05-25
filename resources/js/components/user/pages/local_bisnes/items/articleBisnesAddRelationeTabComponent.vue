<template>
    <div class="width_100">
        <div class="col-md-12">
            <div class="row">
                <h2>Add new relation</h2>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-groupe">
                        <button class="btn btn-primary float-left" @click="add_new_article_value()" >Add new article</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>article</th>
                                <th>|</th>
                                <th>Delite</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="article in new_article_relationes" :key="article.id">
                                <td>
                                    <!-- <form ref="myForm">
                                        <input type="file" name="article" id="article" v-on:change="onFileChange($event, article.id)">
                                    </form>  -->
                                    <form ref="myForm">
                                        <div class="form-group clearfix">
                                            <label for="article_id" class="col-xs-2 control-label"> Article </label>
                                            <div class="col-xs-8">
                                                <select class="form-control" name="article_id" v-on:change="article_select($event, article.id)">
                                                    <option disabled selected="selected"> Select article </option>
                                                    <option v-for="region in regions" :key="region" :value="region.id"> {{ region.url_title }} </option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td>|</td>
                                <td>
                                    <button class="btn btn-danger" @click="del_article(article.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-groupe">
                        <button class="btn btn-primary float-left" @click="add_new_article_value()">Add new article</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    // import { editor_config } from '../../../../mixins/editor/editor_config_mixin.js'
    export default {
        mixins: [
            // editor_config
        ],
        props: [
            // 'article_del_route_prop',
        ],
        data(){
            return {
                new_article_relationes: [],
                old_article_relationes: [],
            }
        },
        mounted() {
            this.get_region_data()
        },
        methods: {
            get_region_data: function () {
                axios
                    .get("/article/get_articles_for_bisnes_suport")
                    .then((response) => {
                        this.regions = response.data;
                    })
                    .catch((error) => console.log(error));
            },

            article_select(event, item_id){
                let id = item_id - 1 
                this.new_article_relationes[id]['article_id'] = event.target.value

                this.update_perent_component_data()
            },
            add_new_article_value(){
                var new_item_id = this.new_article_relationes.length + 1

                this.new_article_relationes.push(
                    {
                        id: new_item_id,
                        article_id: '',
                    }
                );
            },

            del_article(id){
                const objWithIdIndex = this.new_article_relationes.findIndex((obj) => obj.id === id);
                this.new_article_relationes.splice(objWithIdIndex, 1);

                this.update_perent_component_data()
            },
            update_perent_component_data(){
                this.$emit("update_article_relations", this.new_article_relationes);
                
            }
        }
    }
</script>
