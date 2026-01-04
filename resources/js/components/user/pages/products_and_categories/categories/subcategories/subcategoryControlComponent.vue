<template>
    <div class="col-md-12">
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <h2>Subcategorys</h2>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-groupe">
                    <button class="btn btn-primary float-left" @click="openAddSubcategoryModal()">Add new subcategory</button>
                </div>
                <div class="form-groupe">
                    <button class="btn btn-success float-right" @click="get_subcategories_for_category()">Refresh</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>|</th>
                            <th>Edit</th>
                            <th>|</th>
                            <th>Delite</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="subcategory in subcategories" :key="subcategory.id">
                            <td>
                                {{ subcategory.us_name }}
                            </td>
                            <td>|</td>
                            <td>
                                <button class="btn btn-primary" @click="openEditSubcategoryModal(subcategory.id)">Edit</button>
                            </td>
                            <td>|</td>
                            <td>
                                <button class="btn btn-danger" @click="delSubcategory(subcategory.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>
        </div>
        <addSubcategoryModal ref="addSubcategoryModal" @update_list="get_subcategories_for_category" :category_id_prop="category_id_prop"/>
        <editSubcategoryModal ref="editSubcategoryModal" @update_list="get_subcategories_for_category"/>
    </div>
</template>

<script>
    import addSubcategoryModal from './models/addSubcategoryModal.vue';
    import editSubcategoryModal from './models/editSubcategoryModal.vue';

    export default {
        props: [
            'category_id_prop'
        ],
        components: {
            addSubcategoryModal,
            editSubcategoryModal,
        },
        data(){
            return {
                subcategories: [],
                category_id: this.category_id_prop
            }
        },
        mounted() {
            // this.get_subcategories_for_category()
        },
        watch: {
            category_id_prop: function(){
                this.category_id = this.category_id_prop

                this.get_subcategories_for_category()
            },
        },
        methods: {
            openAddSubcategoryModal(){
                this.$refs.addSubcategoryModal.show_modal()

            },
            openEditSubcategoryModal(id){
                this.$refs.editSubcategoryModal.show_modal(id)
            },
            get_subcategories_for_category(){
                axios
                .get("/set_product/set_product_category/set_subcategory/get_subcategories_for_category/" + this.category_id)
                .then(response => {
                    this.subcategories = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            delSubcategory(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .delete("/set_product/set_product_category/set_subcategory/del_subcategory/" + id)
                    .then(response => {
                        // this.subcategories = response.data
                        this.get_subcategories_for_category()
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },
        }
    }
</script>

<style>

</style>