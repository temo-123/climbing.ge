<template>
    <div class="tabs">
        <div class="row">
            
            <input type="radio" name="tabs" id="1" checked="checked">
            <label for="1" v-if="this.table_1_get_route">{{this.table_1_name}}</label>
            <div class="tab" v-if="this.table_1_get_route">
                <!-- <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            Accordion Item #1
                        </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.</div>
                        </div>
                    </div>
                </div> -->
                <div class="add_buttom">
                    <a :href="table_1_add_url" class="btn btn-primary pull-left" type="submit">New </a>
                </div>
                <div class="form-groupe">
                    <button @click="get_data_in_table_1" class="btn main-btn pull-right" v-if="!table_1_is_refresh">Refresh ({{table_1_reset_id}})</button>
                    <span class="badge badge-primare mb-1 pull-right" v-if="table_1_is_refresh">Updating...</span>
                </div>
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

                            <th v-if="table_1_name != 'Users'">|</th>
                            <th style='text-align: center;' v-if="table_1_name == 'Sector'">Region</th>
                            <th style='text-align: center;' v-if="table_1_name == 'Products' && table_1_name != 'Users'">Category</th>
                            <td v-if="table_1_name == 'Products'">|</td>
                            <th style='text-align: center;' v-if="table_1_name != 'Sector' && table_1_name != 'Users'">Published</th>

                            <td v-if="table_1_name == 'Users'">|</td>
                            <th style='text-align: center;' v-if="table_1_name == 'Users'">Role</th>
                            
                            <th>|</th>
                            <th>Edit</th>
                            
                            <th>|</th>
                            <th>Delite</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="table_1_info in table_1" :key="table_1_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_1_info.id}}</td>
                            <!-- <td v-if="table_1_name == 'Sector'">|</td> -->
                            <!-- <td v-if="table_1_name == 'Sector'"><button class="btn btn-primary" @click="show=true">+</button></td> -->
                            <td>|</td>

                            <td v-if="table_1_name == 'Sector'" >
                                <a @click="show=true" href="#">{{table_1_info.name}}</a>
                                <!-- <button class="btn btn-info" @click="show_parmission_edit_madssssel(table_1_info.id)">{{table_1_info.name}}</button> -->
                            </td>
                            <td v-else-if="table_1_name == 'Users'">{{table_1_info.name}} {{table_1_info.surname}}</td>
                            <td v-else>{{table_1_info.url_title}} </td>

                            <td  v-if="table_1_name != 'Users'">|</td>

                            <th style='text-align: center;' v-if="table_1_name == 'Sector'">
                                <div v-for="region in regions" :key="region.id" >
                                    <div v-if="region.id == table_1_info.article_id">
                                        {{region.url_title}}
                                    </div>
                                </div>
                            </th>

                            <td style='text-align: center;' v-if="table_1_name == 'Products' && table_1_name != 'Users'">{{table_1_info.category_id}}</td>
                            <td v-if="table_1_name == 'Products'">|</td>
                            <td style='text-align: center;' v-if="table_1_name != 'Sector' && table_1_name != 'Users'">{{table_1_info.published}}</td>

                            <td v-if="table_1_name == 'Users'">|</td>
                            <th style='text-align: center;' v-if="table_1_name == 'Users'">
                                {{ get_user_role(table_1_info.id) }}
                                {{ user_role }}
                            </th>
                            
                            <td>|</td>

                            <td v-if="table_1_name == 'Users'">
                                <button class="btn btn-primary" @click="show_parmission_edit_madel(table_1_info.id)">Edit roles</button>
                            </td>
                            <td v-else>
                                <a :href="table_1_edit_url+table_1_info.id" class="btn btn-primary" type="submit">Edit</a>
                            </td>
                            
                            <td>|</td>
                            <td>
                                <form method="post" @submit.prevent="table_1_del(table_1_info.id)" >
                                    <input type="hidden" name="_token" >
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <input type="radio" name="tabs" id="2">
            <label for="2" v-if="this.table_2_get_route">{{this.table_2_name}}</label>
            <div class="tab" v-if="this.table_2_get_route">
                <div class="add_buttom">
                    <a :href="table_2_add_url" class="btn btn-primary pull-left" type="submit">New </a>
                </div>
                <div class="form-groupe">
                    <button @click="get_data_in_table_2" class="btn main-btn pull-right" v-if="!table_2_is_refresh">Refresh ({{table_2_reset_id}})</button>
                    <span class="badge badge-primare mb-1 pull-right" v-if="table_2_is_refresh">Updating...</span>
                </div>
                <table class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th style='text-align: center;'>
                                <input type="checkbox" class="all">
                                <!-- <label><input type="checkbox" class="all"></label> -->
                            </th>
                            <th>|</th>
                            <th>ID</th>
                            <th>|</th>
                            <th>Name</th>

                            <th                             v-if="table_2_name != 'Route' && table_2_name != 'Categories' && table_2_name != 'Mounts' && table_2_name != 'Roles'">|</th>
                            <th style='text-align: center;' v-if="table_2_name != 'Route' && table_2_name != 'Categories' && table_2_name != 'Mounts' && table_2_name != 'Roles'">Published</th>
                            
                            <th>|</th>
                            <th v-if="table_1_name != 'Users'">Edit</th>
                            
                            <th v-if="table_1_name != 'Users'">|</th>
                            <th v-if="table_1_name != 'Users'">Delite</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="table_2_info in table_2" :key="table_2_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_2_info.id}}</td>
                            <td>|</td>

                            <td v-if="table_2_name == 'Route'">{{table_2_info.name}}</td>
                            <td v-else-if="table_2_name == 'Categories'">{{table_2_info.us_name}}</td>
                            <td v-else>{{table_2_info.name}} </td>
                            <!-- <td>{{table_2_info.title}}</td> -->

                            <td                             v-if="table_2_name != 'Route' && table_2_name != 'Categories' && table_2_name != 'Mounts' && table_2_name != 'Roles'">|</td>
                            <td style='text-align: center;' v-if="table_2_name != 'Route' && table_2_name != 'Categories' && table_2_name != 'Mounts' && table_2_name != 'Roles'">{{table_2_info.published}}</td>
                            
                            <td>|</td>
                            <td v-if="table_1_name != 'Users'">
                                <a :href="table_2_edit_url+table_2_info.id" class="btn btn-primary" type="submit">Edit</a>
                            </td>
                            
                            <td v-if="table_1_name != 'Users'">|</td>
                            <td v-if="table_1_name != 'Users'">
                                <form method="post" @submit.prevent="table_2_del(table_2_info.id)" >
                                    <input type="hidden" name="_token" >
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <input type="radio" name="tabs" id="3">
            <label for="3"  v-if="this.table_3_get_route">{{this.table_3_name}}</label>
            <div class="tab" v-if="this.table_3_get_route">
                <div class="add_buttom">
                    <a :href="table_3_add_url" class="btn btn-primary pull-left" type="submit">New </a>
                </div>
                <div class="form-groupe">
                    <button @click="get_data_in_table_3" class="btn main-btn pull-right" v-if="!table_3_is_refresh">Refresh ({{table_3_reset_id}})</button>
                    <span class="badge badge-primare mb-1 pull-right" v-if="table_3_is_refresh">Updating...</span>
                </div>
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

                            <th v-if="table_3_name != 'Multi-pitch'">|</th>
                            <th style='text-align: center;' v-if="table_3_name != 'Multi-pitch'">Published</th>
                            
                            <th>|</th>
                            <th>Edit</th>
                            
                            <th>|</th>
                            <th>Delite</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="table_3_info in table_3" :key="table_3_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_3_info.id}}</td>
                            <td>|</td>

                            <td v-if="table_3_name == 'Multi-pitch'">{{table_3_info.name}}</td>
                            <td v-else>{{table_3_info.title}} </td>

                            <td v-if="table_3_name != 'Multi-pitch'">|</td>
                            <td style='text-align: center;' v-if="table_3_name != 'Multi-pitch'">{{table_3_info.published}}</td>
                            
                            <td>|</td>
                            <td>
                                <a :href="table_3_edit_url+table_3_info.id" class="btn btn-primary" type="submit">Edit</a>
                            </td>
                            
                            <td>|</td>
                            <td>
                                <form method="post" @submit.prevent="table_3_del(table_3_info.id)" >
                                    <input type="hidden" name="_token" >
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <input type="radio" name="tabs" id="4">
            <label for="4"  v-if="this.table_4_get_route">{{this.table_4_name}}</label>
            <div class="tab" v-if="this.table_4_get_route">
                <div class="add_buttom">
                    <a :href="table_4_add_url" class="btn btn-primary pull-left" type="submit">New </a>
                </div>
                <div class="form-groupe">
                    <button @click="get_data_in_table_4" class="btn main-btn pull-right" v-if="!table_4_is_refresh">Refresh ({{table_4_reset_id}})</button>
                    <span class="badge badge-primare mb-1 pull-right" v-if="table_4_is_refresh">Updating...</span>
                </div>
                <table class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th style='text-align: center;'>
                                <input type="checkbox" class="all">
                                <!-- <label><input type="checkbox" class="all"></label> -->
                            </th>
                            <th>|</th>
                            <th>ID</th>
                            <th>|</th>
                            <th>Name</th>

                            <th v-if="table_4_name != 'pitches'">|</th>
                            <th style='text-align: center;' v-if="table_4_name != 'pitches'">Published</th>
                            
                            <th>|</th>
                            <th>Edit</th>
                            
                            <th>|</th>
                            <th>Delite</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="table_4_info in table_4" :key="table_4_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_4_info.id}}</td>
                            <td>|</td>

                            <td v-if="table_4_name == 'pitches'">{{table_4_info.name}}</td>
                            <td v-else>{{table_4_info.title}} </td>

                            <td v-if="table_4_name != 'pitches'">|</td>
                            <td style='text-align: center;' v-if="table_4_name != 'pitches'">{{table_4_info.published}}</td>
                            
                            <td>|</td>
                            <td>
                                <a :href="table_4_edit_url+table_4_info.id" class="btn btn-primary" type="submit">Edit</a>
                            </td>
                            
                            <td>|</td>
                            <td>
                                <form method="post" @submit.prevent="table_4_del(table_4_info.id)" >
                                    <input type="hidden" name="_token" >
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        
        </div>


            


        <!-- <SlickList lockAxis="y" v-model="items" tag="table">
            <tr>
                <td>ID</td>
                <td>Num</td>
                <td>Name</td>
            </tr>
            <SlickItem v-for="(item, index) in items" :index="index" :key="index" tag="tr">
                <td style='cursor_mooving'>{{ item.id }}</td>
                <td style='cursor_mooving'>{{ num }}</td>
                <td style='cursor_mooving'>{{ item.name }}</td>
            </SlickItem>
        </SlickList> -->

        <stack-modal
            :show="show"
            title="Modal #1"
            @close="show=false"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: false }"
            :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
            <pre class="language-vue">
                <div class="root">
                    <SlickList lockAxis="y" v-model="items" tag="table">
                        <tr>
                            <td>ID</td>
                            <td>Num</td>
                            <td>Name</td>
                        </tr>
                        <SlickItem v-for="(item, index) in items" :index="index" :key="index" tag="tr">
                            <td>{{ item.id }}</td>
                            <td>{{ num }}</td>
                            <td>{{ item.name }}</td>
                        </SlickItem>
                    </SlickList>
                </div>
            </pre>
        </stack-modal>
        
        <stack-modal
                :show="roles_modal"
                title="Edit roles"
                @close="roles_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form>
                    <select class="form-control" v-model="user_new_parmission"> 
                        <option value="user">User</option> 
                        <option value="ru_menager">English contrnt menager</option> 
                        <option value="ru_menager">Russian contrnt menager</option> 
                        <option value="ka_menager">Georgian contrnt menager</option> 
                        <option value="menager">Content manager</option>
                        <option value="seller">Seller</option>  
                        <option value="admin">Admin</option> 
                    </select>
                    <div class="alert alert-danger" role="alert" v-if="is_parmision_error">
                        {{ parmision_error.user_new_parmission[0] }}
                    </div>
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="edit_permission(1)"
                        >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>
        
    </div>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        props: [
            "table_1_get_route",
            "table_2_get_route",
            "table_3_get_route",
            "table_4_get_route",

            "table_1_name",
            "table_2_name",
            "table_3_name",
            "table_4_name",

            "table_1_add_url",
            "table_2_add_url",
            "table_3_add_url",
            "table_4_add_url",

            "table_1_edit_url",
            "table_2_edit_url",
            "table_3_edit_url",
            "table_4_edit_url",

            "table_1_del_url",
            "table_2_del_url",
            "table_3_del_url",
            "table_4_del_url",

            "table_1_categiry",
            "table_2_categiry",
            "table_3_categiry",
            "table_4_categiry",
        ],
        data() {
            return {
                regions: '',

                items: [
                    {
                        id: 1,
                        name: 'Item 1',
                    },
                    {
                        id: 2,
                        name: 'Item 2',
                    },
                    {
                        id: 3,
                        name: 'Item 3',
                    },
                ],

                show: false,
                roles_modal: false,
                modalClass: '',

                user_role: '',

                parmision_error: '',
                is_parmision_error: false,

                num: 0,

                user_id_for_rditing_parmission: 0,
                user_new_parmission: "",

                table_1: [],
                table_2: [],
                table_3: [],
                table_4: [],

                table_1_is_refresh: false,
                table_2_is_refresh: false,
                table_3_is_refresh: false,
                table_4_is_refresh: false,

                table_1_reset_id: 0,
                table_2_reset_id: 0,
                table_3_reset_id: 0,
                table_4_reset_id: 0,

                url_1: "",
                url_2: "",
                url_3: "",
                url_4: "",
            }
        },

        mounted() {
            if (this.table_1_name == 'Sector') {
                this.get_region_data();
            }
            

            this.get_data_in_table_1();
            this.get_data_in_table_2();
            this.get_data_in_table_3();
            this.get_data_in_table_4();
        },
         
        methods: {
            get_data_in_table_1: function(){
                this.table_1_is_refresh = true

                if (this.table_1_get_route == "/articles/get_article_data/") {
                    this.url_1 = this.table_1_get_route + this.table_1_categiry
                } else {
                    this.url_1 = this.table_1_get_route
                }

                axios
                .get(this.url_1)
                .then(response => {
                    this.table_1 = response.data
                    this.table_1_is_refresh = false
                    this.table_1_reset_id++

                    // this.get_data_in_table_1();
                })
                .catch(
                    error => console.log(error)
                );

            },
            get_data_in_table_2: function(){
                this.table_2_is_refresh = true

                if (this.table_2_get_route == "/articles/get_article_data/") {
                    this.url_2 = this.table_2_get_route + this.table_2_categiry
                } else {
                    this.url_2 = this.table_2_get_route
                }

                axios
                .get(this.url_2)
                .then(response => {
                    this.table_2 = response.data

                    this.table_2_is_refresh = false
                    this.table_2_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_data_in_table_3: function(){
                this.table_3_is_refresh = true

                if (this.table_3_get_route == "/articles/get_article_data/") {
                    this.url_3 = this.table_3_get_route + this.table_3_categiry
                } else {
                    this.url_3 = this.table_3_get_route
                }

                axios
                .get(this.url_3)
                .then(response => {
                    this.table_3 = response.data

                    this.table_3_is_refresh = false
                    this.table_3_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_data_in_table_4: function(){
                this.table_4_is_refresh = true

                if (this.table_4_get_route == "/articles/get_article_data/") {
                    this.url_4 = this.table_4_get_route + this.table_4_categiry
                } else {
                    this.url_4 = this.table_4_get_route
                }

                axios
                .get(this.url_4)
                .then(response => {
                    this.table_4 = response.data

                    this.table_4_is_refresh = false
                    this.table_4_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },


            get_region_data: function(){
                axios
                .get("../routes_and_sectors/get_region_data")
                .then(response => {
                    this.regions = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            
            table_1_del(itemId) {
                axios
                .post(this.table_1_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_1()
                })
                .catch(error => console.log(error))
            },
            table_2_del(itemId) {
                axios
                .post(this.table_2_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_2()
                })
                .catch(error => console.log(error))
            },
            table_3_del(itemId) {
                axios
                .post(this.table_3_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_3()
                })
                .catch(error => console.log(error))
            },
            table_4_del(itemId) {
                axios
                .post(this.table_4_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_4()
                })
                .catch(error => console.log(error))
            },

            show_parmission_edit_madel(user_id){
                this.roles_modal=true;
                this.user_id_for_rditing_parmission = user_id
            },

            edit_permission(user_id) {
                axios
                .post('users/edit_user_permission/' + this.user_id_for_rditing_parmission, {
                    parmission: this.user_new_parmission,
                })
                .then(Response => {
                    this.roles_modal=false
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.parmision_error = error.response.data.errors
                    }
                    this.is_parmision_error = true
                })
            },

            get_user_role(user_id){
                axios
                .post('users/get_role/' + user_id, {
                    user_id: user_id,
                })
                .then(Response => {
                    console.log(Response.data);
                    this.user_role = Response.data
                })
                .catch(error => {
                    this.user_role = "error"
                })
            }
        }
    }
</script>

<style scoped>

    .tabs {
        display: flex;
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