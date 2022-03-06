<template>
    <div class="tabs"> 
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" v-for="but_data in table_data" :key="but_data.id">
                        <input type="radio" :id="but_data.id" :value="but_data.id" v-model="tab_num">
                        
                        <label :for="but_data.id" >{{but_data.table_name}}</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" v-for="filtr_data in table_data" :key="filtr_data.id" v-if="tab_num == filtr_data.id">
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <h3>Filter By</h3> 
                                    <select >
                                        <option v-bind:value="'all'">All</option>
                                        <option v-bind:value="'all'">All2</option>
                                    </select> 
                                </div>

                                <div>
                                    <!-- <h3 v-if="data.table_name == 'Mount routes'">Filter Mount routes By Mounts</h3>  -->
                                    <!-- <select v-if="data.table_name == 'Mount routes'" v-model="value_mount_id">
                                        <option v-bind:value="'all'">All</option>
                                        <option v-for="mount in mounts" :key="mount" v-bind:value="mount.id">{{ mount.name }}</option>
                                    </select>  -->
                                </div>
                                
                                <div>
                                    <!-- <h3 v-if="data.table_name == 'Products'">Filter Products By Category</h3>  -->
                                    <!-- <select v-if="data.table_name == 'Products'" v-model="value_product_category_id">
                                        <option v-bind:value="'all'">All</option>
                                        <option v-for="category in product_categorys" :key="category" v-bind:value="category.id">{{ category.us_name }}</option>
                                    </select> -->
                                </div>


                                <div>
                                    <!-- <h3 v-if="data.table_name == 'Sector'">Filter Sectors By Region</h3>  -->
                                    <!-- <select v-if="data.table_name == 'Sector'" v-model="value_region_id">
                                        <option v-bind:value="'all'">All</option>
                                        <option v-for="region in regions" :key="region" v-bind:value="region.id">{{ region.url_title }}</option>
                                    </select> -->
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="add_buttom float-left">
                                    <!-- <a :href="table_edit_url" class="btn btn-primary pull-left" type="submit">New</a> -->
                                    <a class="btn btn-primary" type="submit">New {{filtr_data.table_name}}</a>
                                </div>

                                <div class="form-groupe float-right">
                                    <!-- <button @click="get_data_in_table_1" class="btn main-btn pull-right" v-if="!table_1_is_refresh">Refresh ({{table_1_reset_id}})</button>
                                    <span class="badge badge-primare mb-1 pull-right" v-if="table_1_is_refresh">Updating...</span> -->

                                    <button class="btn main-btn" >Refresh ({{2}})</button>
                                    <span class="badge badge-primare mb-1">Updating...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                    <!-- <table class="table table-hover" id="dev-table" v-for="data in table_data" :key="data.id" v-if="tab_num == data.id">
                        <thead>
                            <tr>
                                <th style='text-align: center;'>
                                    <input type="checkbox" class="all">
                                </th>
                                <th>|</th>
                                <th>ID</th>
                                <th>|</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody  v-for="info in data.data" :key="info.id">
                            <tr >
                                <td style='text-align: center;'>
                                    <input type="checkbox">
                                </td>
                                <td>|</td>
                                <td>{{info.id}}</td>
                                <td>|</td>
                                <td>{{info.url_title}}</td>
                            </tr>
                        </tbody>
                    </table>  -->


                        <table class="table table-hover" id="dev-table"  v-for="data in table_data" :key="data.id" v-if="tab_num == data.id">
                            <thead>
                                <tr>
                                    <th style='text-align: center;'>
                                        <input type="checkbox" class="all">
                                    </th>
                                    <th>|</th>
                                    <th>ID</th>
                                    <th>|</th>
                                    <th v-if="data.table_name != 'Orders'">Name</th>

                                    <th v-if="data.table_name == 'Orders'">Status</th>

                                    <!-- <th v-if="data.table_name != 'Users' && data.table_name != 'Orders'">|</th> -->

                                    <th style='text-align: center;' v-if="data.table_name == 'Sectors'">|</th>
                                    <th style='text-align: center;' v-if="data.table_name == 'Sectors'">Region</th>

                                    <th style='text-align: center;' v-if="data.table_name == 'Products' && data.table_name != 'Users'">Price</th>

                                    <td v-if="data.table_name == 'Products' && data.table_name != 'Orders'">|</td>
                                    <th style='text-align: center;' v-if="data.table_name == 'Products' && data.table_name != 'Users'">Quantity</th>

                                    <td                             v-if="data.table_name != 'Products' &&
                                                                            data.table_name != 'Sectors' && 
                                                                            data.table_name != 'Routes' && 
                                                                            data.table_name != 'Multi-pitchs' && 
                                                                            data.table_name != 'Pitches' && 
                                                                            data.table_name != 'Users' && 
                                                                            data.table_name != 'Orders'">|</td>

                                    <th style='text-align: center;' v-if="  data.table_name != 'Sectors' && 
                                                                            data.table_name != 'Routes' && 
                                                                            data.table_name != 'Multi-pitchs' && 
                                                                            data.table_name != 'Pitches' && 
                                                                            data.table_name != 'Users' && 
                                                                            data.table_name != 'Orders'">Public</th>

                                    <td v-if="data.table_name == 'Users'">|</td>
                                    <th style='text-align: center;' v-if="data.table_name == 'Users'">Role</th>
                                    
                                    <td v-if="data.table_name == 'Products'">|</td>
                                    <th style='text-align: center;' v-if="data.table_name == 'Products'">Colors</th>

                                    <th                             v-if="data.table_name == 'Routes' || data.table_name == 'Pitches'">|</th>
                                    <th style='text-align: center;' v-if="data.table_name == 'Routes' || data.table_name == 'Pitches'">Grade</th>

                                    <th>|</th>
                                    <th>Edit</th>
                                    
                                    <th>|</th>
                                    <th>Delite</th>
                                    
                                </tr>
                            </thead>
                            
                            <tbody v-if="data.table_name == 'Mount routes'">
                                <tr v-for="table_1_info in data.data" :key="table_1_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_1_info.id}}</td>
                                    <!-- <td v-if="data.table_name == 'Sector'">|</td> -->
                                    <!-- <td v-if="data.table_name == 'Sector'"><button class="btn btn-primary" @click="show=true">+</button></td> -->
                                    <td>|</td>

                                    <td v-if="data.table_name == 'Sector'" >
                                        <a @click="show_sector_model(table_1_info.id)" href="#">{{table_1_info.name}}</a>
                                        <!-- <button class="btn btn-info" @click="show_parmission_edit_madssssel(table_1_info.id)">{{table_1_info.name}}</button> -->
                                    </td>
                                    <td v-else-if="data.table_name == 'Users'">{{table_1_info.name}} {{table_1_info.surname}}</td>
                                    <td v-else>{{table_1_info.url_title}} </td>

                                    <td  v-if="data.table_name != 'Users' || data.table_name != 'Orders'">|</td>

                                    <th style='text-align: center;' v-if="data.table_name == 'Sector'">
                                        <div v-for="region in regions" :key="region.id" >
                                            <div v-if="region.id == table_1_info.article_id">
                                                {{region.url_title}}
                                            </div>
                                        </div>
                                    </th>

                                    <td style='text-align: center;' v-if="data.table_name == 'Products' && data.table_name != 'Users'">{{table_1_info.category_id}}</td>
                                    <td v-if="data.table_name == 'Products'">|</td>
                                    <td style='text-align: center;' v-if="data.table_name != 'Sector' && data.table_name != 'Users'">{{table_1_info.published}}</td>

                                    <td v-if="data.table_name == 'Users'">|</td>
                                    <th style='text-align: center;' v-if="data.table_name == 'Users'">
                                        {{ get_user_role(table_1_info.id) }}
                                        {{ user_role }}
                                    </th>
                                    
                                    <td>|</td>

                                    <td v-if="data.table_name == 'Users'">
                                        <button class="btn btn-primary" @click="show_parmission_edit_madel(table_1_info.id)">Edit roles</button>
                                    </td>
                                    <td v-else>
                                        <a :href="table_1_edit_url+table_1_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <!-- <form method="post" @submit.prevent="table_1_del(table_1_info.id)" >
                                            <input type="hidden" name="_token" >
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                            </div>
                                        </form> -->
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Products'">
                                <tr v-for="table_1_info in data.data" :key="table_1_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_1_info.id}}</td>
                                    <td>|</td>
                                    <td>{{table_1_info.url_title}} </td>
                                    <td>|</td>
                                    <td style='text-align: center;'>{{table_1_info.price}} {{table_1_info.currency}}</td>
                                    <td>|</td>
                                    <td style='text-align: center;'>{{table_1_info.quantity}}</td>
                                    <td>|</td>
                                    <td style='text-align: center;'>{{table_1_info.published}}</td>
                                    <td>|</td>
                                    <td>
                                        <a href="#" class="btn btn-warning" type="submit">Colors</a>
                                    </td>
                                    <td>|</td>
                                    <td>
                                        <a :href="table_1_edit_url+table_1_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <!-- <form method="post" @submit.prevent="table_1_del(table_1_info.id)" >
                                            <input type="hidden" name="_token" >
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                            </div>
                                        </form> -->
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Sectors'">
                                <tr v-for="table_1_info in data.data" :key="table_1_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_1_info.id}}</td>
                                    <td>|</td>

                                    <td v-if="data.table_name == 'Sectors'" >
                                        <a @click="show_sector_model(table_1_info.id)" href="#">{{table_1_info.name}}</a>
                                    </td>
                                    <td>|</td>

                                    <th style='text-align: center;' v-if="data.table_name == 'Sectors'">
                                        <div v-for="region in regions" :key="region.id" >
                                            <div v-if="region.id == table_1_info.article_id">
                                                {{region.url_title}}
                                            </div>
                                        </div>
                                    </th>
                                    
                                    <td>|</td>

                                    <td >
                                        <a :href="table_1_edit_url+table_1_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <!-- <form method="post" @submit.prevent="table_1_del(table_1_info.id)" >
                                            <input type="hidden" name="_token" >
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                            </div>
                                        </form> -->
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name === 'Routes'">
                                <tr v-for="table_2_info in data.data" :key="table_2_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_2_info.id}}</td>
                                    <td>|</td>

                                    <td v-if="data.table_name == 'Routes'">{{table_2_info.name}}</td>
                                    <td v-else-if="data.table_name == 'Categories'">{{table_2_info.us_name}}</td>
                                    <td v-else>{{table_2_info.name}} </td>


                                    <td v-if="data.table_name == 'Routes'">|</td>
                                    <td v-if="data.table_name == 'Routes'">
                                                                    <div v-if="table_2_info.or_grade != NULL">{{table_2_info.grade}} / {{ table_2_info.or_grade }}</div>
                                                                    <div v-else>{{table_2_info.grade}}</div>
                                    </td>

                                    <td>|</td>
                                    <td v-if="table_1_name != 'Users'">
                                        <a :href="table_2_edit_url+table_2_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td v-if="table_1_name != 'Users'">|</td>
                                    <td v-if="table_1_name != 'Users'">
                                        <!-- <form method="post" @submit.prevent="table_2_del(table_2_info.id)" >
                                            <input type="hidden" name="_token" >
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                            </div>
                                        </form> -->
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Multi-pitchs'">
                                <tr v-for="table_3_info in data.data" :key="table_3_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_3_info.id}}</td>
                                    <td>|</td>

                                    <!-- <td v-if="data.table_name == 'Multi-pitch'">{{table_3_info.name}}</td> -->
                                    <td v-if="data.table_name == 'Multi-pitchs'">
                                        <a @click="show_mtp_model(table_3_info.id)" href="#">{{table_3_info.name}}</a>
                                    </td>
                                    <td v-else>{{table_3_info.title}} </td>

                                    <td v-if="data.table_name != 'Multi-pitchs'">|</td>
                                    <td style='text-align: center;' v-if="data.table_name != 'Multi-pitchs'">{{table_3_info.published}}</td>
                                    
                                    <td>|</td>
                                    <td>
                                        <a :href="table_3_edit_url+table_3_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <!-- <form method="post" @submit.prevent="table_3_del(table_3_info.id)" >
                                            <input type="hidden" name="_token" >
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                            </div>
                                        </form> -->
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="data.table_name == 'Pitches'">
                                <tr v-for="table_4_info in data.data" :key="table_4_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_4_info.id}}</td>

                                    <td>|</td>

                                    <td v-if="data.table_name == 'Pitches'">
                                        {{table_4_info.name}}
                                    </td>

                                    <td v-else>{{table_4_info.title}} </td>

                                    <td v-if="data.table_name != 'Pitches'">|</td>
                                    <td style='text-align: center;' v-if="data.table_name != 'Pitches'">{{table_4_info.published}}</td>
                                    
                                    <td>|</td>
                                    <td v-if="data.table_name == 'Pitches'">
                                                                    <div v-if="table_4_info.or_grade != NULL">{{table_4_info.grade}} / {{ table_4_info.or_grade }}</div>
                                                                    <div v-else>{{table_4_info.grade}}</div>
                                    </td>

                                    <td>|</td>
                                    <td>
                                        <a :href="table_4_edit_url+table_4_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <!-- <form method="post" @submit.prevent="table_4_del(table_4_info.id)" >
                                            <input type="hidden" name="_token" >
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                            </div>
                                        </form> -->
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr v-for="table_1_info in data.data" :key="table_1_info.id">
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_1_info.id}}</td>
                                    <td>|</td>

                                    <!-- <div v-if="data.table_name != 'Orders'"> -->
                                        <!-- <td v-if="data.table_name == 'Sector'" > -->
                                        <!-- <a v-if="data.table_name != 'Orders' && data.table_name == 'Sector'" @click="show_sector_model(table_1_info.id)" href="#">{{table_1_info.name}}</a>
                                        </td>
                                        <td v-else-if="data.table_name != 'Orders' && data.table_name == 'Users'">{{table_1_info.name}} {{table_1_info.surname}}</td> -->
                                        <td>{{table_1_info.url_title}} </td>
                                    <!-- </div> -->

                                    <td  v-if="data.table_name != 'Users' && data.table_name != 'Orders'">|</td>

                                    <!-- <th style='text-align: center;' v-if="data.table_name == 'Sector'">
                                        <div v-for="region in regions" :key="region.id" >
                                            <div v-if="region.id == table_1_info.article_id">
                                                {{region.url_title}}
                                            </div>
                                        </div>
                                    </th> -->

                                    <!-- <td style='text-align: center;' v-if="data.table_name == 'Products' && data.table_name != 'Users'">{{table_1_info.category_id}}</td>
                                    <td v-if="data.table_name == 'Products'">|</td> -->
                                    <td style='text-align: center;' v-if="data.table_name != 'Sector' && data.table_name != 'Users' && data.table_name != 'Orders'">{{table_1_info.published}}</td>
                                    <!-- <td v-if="data.table_name == 'Orders'">{{table_1_info.status}}</td>
                                    <td v-if="data.table_name == 'Users'">|</td> -->

                                    <th style='text-align: center;' v-if="data.table_name == 'Users'">
                                        <div v-for="user_role in user_roles" :key="user_role.id" v-if='user_role.user_id == table_1_info.id'>
                                            <div v-if="data.table_name == 'Roles'">
                                                <div v-for="table_2_info in table_2" :key="table_2_info.id" v-if='user_role.role_id == table_2_info.id'>
                                                    {{ table_2_info.name }}
                                                </div>
                                            </div>
                                            <div v-else>
                                                {{ user_role.role_id }}
                                            </div>
                                        </div>
                                    </th>
                                    
                                    <td>|</td>

                                    <td v-if="data.table_name == 'Users'">
                                        <button class="btn btn-primary" @click="show_parmission_edit_madel(table_1_info.id)">Edit roles</button>
                                    </td>
                                    <td v-if="data.table_name == 'Orders'">
                                        <button class="btn btn-primary" @click="show_order_status_edit_madel(table_1_info.id)">Edit Status</button>
                                    </td>
                                    <td v-else>
                                        <a :href="table_1_edit_url+table_1_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <!-- <form method="post" @submit.prevent="table_1_del(table_1_info.id)" >
                                            <input type="hidden" name="_token" >
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                            </div>
                                        </form> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import tabComponent  from './TabComponent'
    export default {
        components: {
            // tabComponent ,
        },

        props: [
            'table_data',
        ],

        data(){
            return {
                tab_num: 1,
                picked: ''
                // id: 0,
                // num: 1,
            }
        },

        mounted() {
            this.tab_num = 1
            // this.num = this.table_data.length
            // alert(this.table_data.length)
        },

        methods: {
            // data_tab_selection(id){
            //     alert(id)
            // },
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