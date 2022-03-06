<template>
    <div class="tabs">            
        <div class="row">
            <input type="radio" name="tabs" id="1" checked="checked">
            <label for="1" v-if="this.table_1_get_route">{{this.table_1_name}}</label>
            <div class="tab" v-if="this.table_1_get_route">
                
                <div>
                    <h3 v-if="table_1_name == 'Mount routes'">Filter Mount routes By Mounts</h3> 
                    <select v-if="table_1_name == 'Mount routes'" v-model="value_mount_id">
                        <option v-bind:value="'all'">All</option>
                        <option v-for="mount in mounts" :key="mount" v-bind:value="mount.id">{{ mount.name }}</option>
                    </select> 
                </div>

                <!-- <ul v-if="table_1_name == 'Mount routes'">
                    <li v-for="product in filterProductsByCategory">Product Name : {{product.name}} - Price : {{product.price}} ({{product.category}})</li>
                </ul> -->
                
                <div>
                    <h3 v-if="table_1_name == 'Products'">Filter Products By Category</h3> 
                    <select v-if="table_1_name == 'Products'" v-model="value_product_category_id">
                        <option v-bind:value="'all'">All</option>
                        <option v-for="category in product_categorys" :key="category" v-bind:value="category.id">{{ category.us_name }}</option>
                    </select>
                </div>


                <div>
                    <h3 v-if="table_1_name == 'Sector'">Filter Sectors By Region</h3> 
                    <select v-if="table_1_name == 'Sector'" v-model="value_region_id">
                        <option v-bind:value="'all'">All</option>
                        <option v-for="region in regions" :key="region" v-bind:value="region.id">{{ region.url_title }}</option>
                    </select>
                </div>


                <div class="add_buttom">
                    <a :href="table_1_add_url" class="btn btn-primary pull-left" type="submit">New</a>
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
                            <th v-if="table_1_name != 'Orders'">Name</th>

                            <th v-if="table_1_name == 'Orders'">Status</th>

                            <th v-if="table_1_name != 'Users' && table_1_name != 'Orders'">|</th>
                            <th style='text-align: center;' v-if="table_1_name == 'Sector'">Region</th>
                            <th style='text-align: center;' v-if="table_1_name == 'Products' && table_1_name != 'Users'">Price</th>
                            <td v-if="table_1_name == 'Products' && table_1_name != 'Orders'">|</td>
                            <th style='text-align: center;' v-if="table_1_name == 'Products' && table_1_name != 'Users'">Quantity</th>
                            <td v-if="table_1_name == 'Products' && table_1_name != 'Orders'">|</td>
                            <th style='text-align: center;' v-if="table_1_name != 'Sector' && table_1_name != 'Users' && table_1_name != 'Orders'">Public</th>

                            <td v-if="table_1_name == 'Users'">|</td>
                            <th style='text-align: center;' v-if="table_1_name == 'Users'">Role</th>
                            
                            <td v-if="table_1_name == 'Products'">|</td>
                            <th style='text-align: center;' v-if="table_1_name == 'Products'">Colors</th>

                            <th>|</th>
                            <th>Edit</th>
                            
                            <th>|</th>
                            <th>Delite</th>
                            
                        </tr>
                    </thead>
                    <tbody v-if="table_1_name == 'Mount routes'">
                        <tr v-for="table_1_info in filterMountsByMount_system" :key="table_1_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_1_info.id}}</td>
                            <!-- <td v-if="table_1_name == 'Sector'">|</td> -->
                            <!-- <td v-if="table_1_name == 'Sector'"><button class="btn btn-primary" @click="show=true">+</button></td> -->
                            <td>|</td>

                            <td v-if="table_1_name == 'Sector'" >
                                <a @click="show_sector_model(table_1_info.id)" href="#">{{table_1_info.name}}</a>
                                <!-- <button class="btn btn-info" @click="show_parmission_edit_madssssel(table_1_info.id)">{{table_1_info.name}}</button> -->
                            </td>
                            <td v-else-if="table_1_name == 'Users'">{{table_1_info.name}} {{table_1_info.surname}}</td>
                            <td v-else>{{table_1_info.url_title}} </td>

                            <td  v-if="table_1_name != 'Users' || table_1_name != 'Orders'">|</td>

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
                    <tbody v-else-if="table_1_name == 'Products'">
                        <tr v-for="table_1_info in filterProductsByCategory" :key="table_1_info.id">
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
                                <form method="post" @submit.prevent="table_1_del(table_1_info.id)" >
                                    <input type="hidden" name="_token" >
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else-if="table_1_name == 'Sector'">
                        <tr v-for="table_1_info in filterSectorsByRegion" :key="table_1_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_1_info.id}}</td>
                            <td>|</td>

                            <td v-if="table_1_name == 'Sector'" >
                                <a @click="show_sector_model(table_1_info.id)" href="#">{{table_1_info.name}}</a>
                            </td>
                            <td>|</td>

                            <th style='text-align: center;' v-if="table_1_name == 'Sector'">
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
                                <form method="post" @submit.prevent="table_1_del(table_1_info.id)" >
                                    <input type="hidden" name="_token" >
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr v-for="table_1_info in table_1" :key="table_1_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_1_info.id}}</td>
                            <td>|</td>

                            <div v-if="table_1_name != 'Orders'">
                                <td v-if="table_1_name == 'Sector'" >
                                    <a @click="show_sector_model(table_1_info.id)" href="#">{{table_1_info.name}}</a>
                                </td>
                                <td v-else-if="table_1_name == 'Users'">{{table_1_info.name}} {{table_1_info.surname}}</td>
                                <td v-else>{{table_1_info.url_title}} </td>
                            </div>

                            <td  v-if="table_1_name != 'Users' && table_1_name != 'Orders'">|</td>

                            <th style='text-align: center;' v-if="table_1_name == 'Sector'">
                                <div v-for="region in regions" :key="region.id" >
                                    <div v-if="region.id == table_1_info.article_id">
                                        {{region.url_title}}
                                    </div>
                                </div>
                            </th>

                            <td style='text-align: center;' v-if="table_1_name == 'Products' && table_1_name != 'Users'">{{table_1_info.category_id}}</td>
                            <td v-if="table_1_name == 'Products'">|</td>
                            <td style='text-align: center;' v-if="table_1_name != 'Sector' && table_1_name != 'Users' && table_1_name != 'Orders'">{{table_1_info.published}}</td>
                            <td v-if="table_1_name == 'Orders'">{{table_1_info.status}}</td>
                            <td v-if="table_1_name == 'Users'">|</td>

                            <th style='text-align: center;' v-if="table_1_name == 'Users'">
                                <div v-for="user_role in user_roles" :key="user_role.id" v-if='user_role.user_id == table_1_info.id'>
                                    <div v-if="table_2_name == 'Roles'">
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

                            <td v-if="table_1_name == 'Users'">
                                <button class="btn btn-primary" @click="show_parmission_edit_madel(table_1_info.id)">Edit roles</button>
                            </td>
                            <td v-if="table_1_name == 'Orders'">
                                <button class="btn btn-primary" @click="show_order_status_edit_madel(table_1_info.id)">Edit Status</button>
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

                <!-- <div class="row"> -->
                    <div>
                        <h3 v-if="table_1_name == 'Sector'">Filter Routes sectors By Region</h3> 
                        <select v-if="table_1_name == 'Sector'" v-model="value_region_id">
                            <option v-bind:value="'all'">All</option>
                            <option v-for="region in regions" :key="region" v-bind:value="region.id">{{ region.url_title }}</option>
                        </select>
                    </div>
                    <div>
                        <h3 v-if="table_2_name == 'Route'">Filter Routes By Sector</h3> 
                        <select v-if="table_2_name == 'Route'" v-model="value_sector_id">
                            <option v-bind:value="'all'">All</option>
                            <option v-for="sector in sectors" :key="sector" v-bind:value="sector.id">{{ sector.name }}</option>
                        </select>
                    </div>
                <!-- </div> -->

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
                            <th v-if="table_1_name != 'Orders'">Name</th>

                            <th v-if="table_1_name == 'Orders'">Status</th>

                            <th                             v-if="table_2_name != 'Route' && table_2_name != 'Categories' && table_2_name != 'Mounts' && table_2_name != 'Roles'">|</th>
                            <th style='text-align: center;' v-if="table_2_name != 'Route' && table_2_name != 'Categories' && table_2_name != 'Mounts' && table_2_name != 'Roles' && table_1_name != 'Orders'">Published</th>
                            
                            <th v-if="table_1_name == 'Orders'">Detals</th>

                            <th                             v-if="table_2_name == 'Route' ">|</th>
                            <th style='text-align: center;' v-if="table_2_name == 'Route' ">Grade</th>
                            
                            <th>|</th>

                            <th v-if="table_1_name != 'Users' && table_1_name != 'Orders'">Edit</th>
                            
                            <th v-if="table_1_name != 'Users' && table_1_name != 'Orders'">|</th>
                            <th v-if="table_1_name != 'Users' && table_1_name != 'Orders'">Delite</th>
                            
                        </tr>
                    </thead>
                    <tbody v-if="table_2_name === 'Route'">
                        <tr v-for="table_2_info in filterRoutesBySector" :key="table_2_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_2_info.id}}</td>
                            <td>|</td>

                            <td v-if="table_2_name == 'Route'">{{table_2_info.name}}</td>
                            <td v-else-if="table_2_name == 'Categories'">{{table_2_info.us_name}}</td>
                            <td v-else>{{table_2_info.name}} </td>


                            <td v-if="table_2_name == 'Route'">|</td>
                            <td v-if="table_2_name == 'Route'">
                                                            <div v-if="table_2_info.or_grade != NULL">{{table_2_info.grade}} / {{ table_2_info.or_grade }}</div>
                                                            <div v-else>{{table_2_info.grade}}</div>
                            </td>

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
                    <tbody v-else> 
                        <tr v-for="table_2_info in table_2" :key="table_2_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_2_info.id}}</td>
                            <td>|</td>

                            <!-- <div  v-if="table_1_name != 'Orders'"> -->
                                <td v-if="table_2_name == 'Route'">{{table_2_info.name}}</td>
                                <td v-else-if="table_2_name == 'Categories'">{{table_2_info.us_name}}</td>
                                <td v-else-if="table_2_info.name">{{table_2_info.name}} </td>
                                <!-- <td>{{table_2_info.title}}</td> -->
                            <!-- </div> -->

                            <td                             v-if="table_2_name != 'Route' && table_2_name != 'Categories' && table_2_name != 'Mounts' && table_2_name != 'Roles' && table_1_name != 'Orders'">|</td>
                            <td style='text-align: center;' v-if="table_2_name != 'Route' && table_2_name != 'Categories' && table_2_name != 'Mounts' && table_2_name != 'Roles' && table_1_name != 'Orders'">{{table_2_info.published}}</td>
                            
                            <td v-if="table_1_name == 'Orders'">{{table_2_info.status}}</td>

                            <td>|</td>

                            <td v-if="table_1_name == 'Orders'">
                                <button type="submit" class="btn btn-primary" @click="show_order_detals_madel(table_2_info.id)">More detals</button>
                            </td>

                            <td v-if="table_1_name != 'Users' && table_1_name != 'Orders'">
                                <a :href="table_2_edit_url+table_2_info.id" class="btn btn-primary" type="submit">Edit</a>
                            </td>
                            
                            <td v-if="table_1_name != 'Users'">|</td>
                            <td v-if="table_1_name != 'Users' && table_1_name != 'Orders'">
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

                <div>
                    <h3 v-if="table_1_name == 'Sector'">Filter Multi-pitch sectors By Region</h3> 
                    <select v-if="table_1_name == 'Sector'" v-model="value_region_id">
                        <option v-bind:value="'all'">All</option>
                        <option v-for="region in regions" :key="region" v-bind:value="region.id">{{ region.url_title }}</option>
                    </select>
                </div>
                <div>
                    <h3 v-if="table_3_name == 'Multi-pitch'">Filter Multi-pitch By Sector</h3> 
                    <select v-if="table_3_name == 'Multi-pitch'" v-model="value_mtp_sector_id">
                        <option v-bind:value="'all'">All</option>
                        <option v-for="sector in sectors" :key="sector" v-bind:value="sector.id">{{ sector.name }}</option>
                    </select>
                </div>

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
                    <tbody v-if="table_3_name == 'Multi-pitch'">
                        <tr v-for="table_3_info in filterMTPBySector" :key="table_3_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_3_info.id}}</td>
                            <td>|</td>

                            <!-- <td v-if="table_3_name == 'Multi-pitch'">{{table_3_info.name}}</td> -->
                            <td v-if="table_3_name == 'Multi-pitch'">
                                <a @click="show_mtp_model(table_3_info.id)" href="#">{{table_3_info.name}}</a>
                            </td>
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
                    <tbody v-else>
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
                <div>
                    <h3 v-if="table_1_name == 'Sector'">Filter Multi-pitch sectors By Region</h3> 
                    <select v-if="table_1_name == 'Sector'" v-model="value_region_id">
                        <option v-bind:value="'all'">All</option>
                        <option v-for="region in regions" :key="region" v-bind:value="region.id">{{ region.url_title }}</option>
                    </select>
                </div>
                <div>
                    <h3 v-if="table_3_name == 'Multi-pitch'">Filter Multi-pitch By Sector</h3> 
                    <select v-if="table_3_name == 'Multi-pitch'" v-model="value_mtp_sector_id">
                        <option v-bind:value="'all'">All</option>
                        <option v-for="sector in sectors" :key="sector" v-bind:value="sector.id">{{ sector.name }}</option>
                    </select>
                </div>
                <div>
                    <h3 v-if="table_4_name == 'pitches'">Filter pitches By Multi-pitch</h3> 
                    <select v-if="table_4_name == 'pitches'" v-model="value_mtp_id">
                        <option v-bind:value="'all'">All</option>
                        <option v-for="MTP in MTPs" :key="MTP" v-bind:value="MTP.id">{{ MTP.name }}</option>
                    </select>
                </div>

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

                            <th v-if="table_4_name == 'pitches'">|</th>
                            <th style='text-align: center;' v-if="table_4_name == 'pitches'">Grade</th>
                            
                            <th>|</th>
                            <th>Edit</th>
                            
                            <th>|</th>
                            <th>Delite</th>
                            
                        </tr>
                    </thead>
                    <tbody v-if="table_4_name == 'pitches'">
                        <tr v-for="table_4_info in filterMTP_PitchByMTP" :key="table_4_info.id">
                            <td style='text-align: center;'>
                                <input type="checkbox">
                            </td>
                            <td>|</td>
                            <td>{{table_4_info.id}}</td>

                            <td>|</td>

                            <td v-if="table_4_name == 'pitches'">
                                {{table_4_info.name}}
                            </td>

                            <td v-else>{{table_4_info.title}} </td>

                            <td v-if="table_4_name != 'pitches'">|</td>
                            <td style='text-align: center;' v-if="table_4_name != 'pitches'">{{table_4_info.published}}</td>
                            
                            <td>|</td>
                            <td v-if="table_4_name == 'pitches'">
                                                            <div v-if="table_4_info.or_grade != NULL">{{table_4_info.grade}} / {{ table_4_info.or_grade }}</div>
                                                            <div v-else>{{table_4_info.grade}}</div>
                            </td>

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
                    <tbody v-else>
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
        
        <stack-modal
            :show="show_sector_modal"
            title="Sector"
            @close="show_sector_modal=false"
            :modal-class="{ [SectorModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
            <pre class="language-vue">
                <div class="root">
                    <div class="col-md-12">
                        <div class="row">
                            <img v-for="sector_image in sector_images" :key="sector_image.id" :src="'/public/images/sector_img/'+sector_image.image" alt="image" :style="'width:' + sector_images_size + '%'">
                        </div>
                        <div class="row">
                            <h2 v-if="sector_routes != ''">Sport climbing routes</h2>
                            <SlickList lockAxis="y" v-model="sector_routes" v-if="sector_routes != ''" tag="table" style="width: 100%">
                                <tr>
                                    <td>ID</td>
                                    <td>Num</td>
                                    <td>Name</td>
                                    <td>Grade</td>
                                    <td>Height</td>
                                    <td>Bolts</td>
                                    <td>Bolter</td>
                                    <td>First ascent</td>
                                    <td>Edit</td>
                                </tr>
                                <SlickItem v-for="(route, index) in sector_routes" :index='index' :key="index" tag="tr">
                                    <td>{{ route.id }}</td>
                                    <td>{{ route.num }}</td>
                                    <td>{{ route.name }}</td>
                                    <!-- <td>{{ route.grade }}</td> -->
                                    <td v-if="route.or_grade != NULL">{{route.grade}} / {{ route.or_grade }}</td>
                                    <td v-if="route.or_grade == NULL">{{route.grade}}</td>
                                    <td>{{ route.height }}</td>
                                    <td>{{ route.bolts }}</td>
                                    <td>{{ route.bolter }}</td>
                                    <td>{{ route.first_ascent }}</td>
                                    <td><a :href="table_2_edit_url+route.id" class="btn btn-primary" type="submit">Edit</a></td>
                                </SlickItem>
                            </SlickList>

                            <h2 v-if="sector_mtps != ''">Mupli pitchs</h2>

                            <SlickList lockAxis="y" v-model="sector_mtps" v-if="sector_mtps != ''" tag="table" style="width: 100%">
                                <tr>
                                    <td>ID</td>
                                    <td>Num</td>
                                    <td>Name</td>
                                </tr>
                                <SlickItem v-for="(mtp, index) in sector_mtps" :index='index' :key="index" tag="tr">
                                    <td>{{ mtp.id }}</td>
                                    <td>{{ mtp.num }}</td>
                                    <td>{{ mtp.name }}</td>
                                </SlickItem>
                            </SlickList>
                        </div>
                    </div>
                </div>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="save_routes_sequence()"
                        >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
            :show="show_mtp_modal"
            title="Multy-pitch"
            @close="show_mtp_modal=false"
            :modal-class="{ [SectorModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
            <pre class="language-vue">
                <div class="root">
                    <div class="col-md-12">
                        <div class="row">
                            <SlickList lockAxis="y" v-model="sector_mtp_pitchs_for_modal" tag="table" style="width: 100%">
                                <tr>
                                    <td>ID</td>
                                    <td>Num</td>
                                    <td>Name</td>
                                    <td>Grade</td>
                                    <td>Height</td>
                                    <td>Bolts</td>
                                    <td>Bolter</td>
                                    <td>First ascent</td>
                                </tr>
                                <SlickItem v-for="(pitch, index) in sector_mtp_pitchs_for_modal" :index='index' :key="index" tag="tr">
                                    <td>{{ pitch.id }}</td>
                                    <td>{{ pitch.num }}</td>
                                    <td>{{ pitch.name }}</td>
                                    <td>{{ pitch.grade }}</td>
                                    <td>{{ pitch.height }}</td>
                                    <td>{{ pitch.bolts }}</td>
                                    <td>{{ pitch.bolter }}</td>
                                    <td>{{ pitch.first_ascent }}</td>
                                </SlickItem>
                            </SlickList>
                        </div>
                    </div>
                </div>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="save_pitchs_sequence()"
                        >
                    Save
                    </button>
                </div>
            </div>
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
                        <option value="manager">Content manager</option>
                        <option value="seller">Seller</option>  
                        <option value="admin">Admin</option> 
                    </select>
                    <!-- <div class="alert alert-danger" role="alert" v-if="is_parmision_error"> -->
                    <p class="alert alert-danger" role="alert" v-if="is_parmision_error">
                        {{ parmision_error.parmission[0] }}
                    </p>
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="edit_permission(user_id_for_rditing_parmission)"
                        >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="edit_order_modal"
                title="Edit order status"
                @close="edit_order_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <div class="container">
                    <div class="clearfix row">
                        <p>
                            Order ID - {{  modal_order.id }}
                            Shiping data - {{  modal_order.order_data }}
                            Status - {{ modal_order.status }}
                            User Id - {{  modal_order.user_id }}
                        </p>
                    </div>
                    <hr>
                    <div class="clearfix row">
                        <p>
                            Adres ID -
                            Address N1 - 
                            Address N2 - 
                            ZIP - 
                            Quntry - 
                            City -
                        </p>
                    </div>
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>4</td>
                                <td>test</td>
                                <td>5</td>
                                <td>25</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2"> </th>
                                <th class="text-center">Total</th>
                                <th class="text-right">25</th>
                            </tr>
                        </tfoot>
                    </table>
                    <form ref="myForm">
                            <div class="clearfix row">
                                <div class="col-md-12 image_add_modal_form">
                                    <select class="form-control" name="status" id="status">
                                            <option value="1">Order Processed</option> 
                                            <option value="2">Order Shipped</option>
                                            <option value="3">Order En Route</option>
                                            <option value="4">Order Arrived</option>
                                    </select> 
                                </div>
                        </div>
                    </form>
                    <form >
                        <div class="clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="email" class="form-control" v-model="email" name="email" id="email" placeholder="email" title="enter your email.">
                            </div>
                        </div>
                        <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="edit_permission(user_id_for_rditing_parmission)"
                        >
                        Send invois to mail
                        </button>
                    </form>
                </div>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="edit_permission(user_id_for_rditing_parmission)"
                        >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="order_detals_modal"
                title="Order status"
                @close="order_detals_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre>
                <div class="container">
                    <p>
                        Order ID - #
                        Status - 
                        Shiping data - 
                    </p>
                    <hr>
                    <p>
                        Address N1 - 
                        Address N2 - 
                        ZIP - 
                        Quntry - 
                        City -
                    </p>
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>4</td>
                                <td>test</td>
                                <td>5</td>
                                <td>25</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2"> </th>
                                <th class="text-center">Total</th>
                                <th class="text-right">25</th>
                            </tr>
                        </tfoot>
                    </table>
                    <p>
                        Delivery asushistvitsa in the next 7-10 days.
                        Delivery times - 
                    </p>
                </div>
            </pre>
            <div slot="modal-footer">
            </div>
        </stack-modal>

    </div>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        computed: {
            filterProductsByCategory: function(){
                if(this.value_product_category_id == "" || this.value_product_category_id == "all"){
                    return this.products
                }
                return this.products.filter(product => product.category_id == this.value_product_category_id)
                // return this.products.filter(product => !product.category.indexOf(this.category))
            },
            filterSectorsByRegion: function(){
                if(this.value_region_id == "" || this.value_region_id == "all"){
                    return this.sectors
                }
                return this.sectors.filter(sector => sector.article_id == this.value_region_id)
            },
            filterRoutesBySector: function(){
                if(this.value_sector_id == "" || this.value_sector_id == "all"){
                    return this.routes
                }
                return this.routes.filter(route => route.sector_id == this.value_sector_id)
            },
            filterMTPBySector: function(){
                if(this.value_mtp_sector_id == "" || this.value_mtp_sector_id == "all"){
                    return this.MTPs
                }
                return this.MTPs.filter(MTPs => MTPs.sector_id == this.value_mtp_sector_id)
            },
            filterMTP_PitchByMTP: function(){
                if(this.value_mtp_id == "" || this.value_mtp_id == "all"){
                    return this.MTP_pitchs
                }
                return this.MTP_pitchs.filter(MTP_pitch => MTP_pitch.mtp_id == this.value_mtp_id)
            },
            filterMountsByMount_system: function(){
                if(this.value_mount_id == "" || this.value_mount_id == "all"){
                    return this.mount_routes
                }
                return this.mount_routes.filter(mount_route => mount_route.mount_id == this.value_mount_id)
            }
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
                items: ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5', 'Item 6', 'Item 7', 'Item 8'],
                product_categorys: '',
                products: '',

                sector_routes: "",
                sector_images: "",
                sector_images_size: "",
                sector_mtps: "",
                sector_mtp_pitchs: "",
                model_tible: "",

                routes: "",
                sectors: "",
                regions: "",
                MTPs: "",
                MTP_pitchs: "",
                mount_routes: "",
                mounts: "",

                modal_order: "",

                value_route_id: "",
                value_mtp_id: "",
                value_mtp_sector_id: "",
                value_sector_id: "",
                value_region_id: "",
                value_product_category_id: "",
                value_mount_id: "",

                show_sector_modal: false,
                show_mtp_modal: false,
                order_detals_modal: false,
                roles_modal: false,
                edit_order_modal: false,
                SectorModalClass: 'modal-xxxl',
                modalClass: '',

                sector_mtp_pitchs_for_modal: '',

                user_roles: '',

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
            this.get_data_in_table_1();
            this.get_data_in_table_2();
            this.get_data_in_table_3();
            this.get_data_in_table_4();

            if (this.table_1_name == 'Sector') {
                this.get_sectors_data();
                this.get_region_data();
                this.get_routes_data();
                this.get_MTP_data()
                this.get_MTP_pitch_data()
            }
            if (this.table_1_name == 'Products') {
                this.get_product_category_data();
                this.get_product_data();
            }
            if (this.table_1_name == 'Mount routes') {
                this.get_mount_route_data();
                this.get_mount_data();
            }
            if(this.table_1_name == 'Users'){
                this.get_user_role()
            }
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


            
            table_1_del(itemId) {
                axios
                .post(this.table_1_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    if (this.table_1_name == 'Sector') {
                        this.get_sectors_data();
                    }
                    if (this.table_1_name == 'Products') {
                        this.get_product_data();
                    }
                    if (this.table_1_name == 'Mount routes') {
                        this.get_mount_route_data();
                    }

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
                    this.get_data_in_table_2()

                    if (this.table_2_name == 'Route') {
                        this.get_route_data();
                    }
                })
                .catch(error => console.log(error))
            },
            table_3_del(itemId) {
                axios
                .post(this.table_3_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    this.get_data_in_table_3()

                    if (this.table_3_name == 'Multi-pitch') {
                        this.get_MTP_data();
                    }
                })
                .catch(error => console.log(error))
            },
            table_4_del(itemId) {
                axios
                .post(this.table_4_del_url + itemId, {
                    id: itemId,
                })
                .then(Response => {
                    this.get_data_in_table_4()

                    if (this.table_3_name == 'pitches') {
                        this.get_MTP_pitch_data();
                    }
                })
                .catch(error => console.log(error))
            },



            show_parmission_edit_madel(user_id){
                this.roles_modal=true;
                this.user_id_for_rditing_parmission = user_id
            },
            edit_permission(id) {
                axios
                .post('users/edit_user_permission/' + id, {
                    parmission: this.user_new_parmission,
                })
                .then((response)=> { 
                    this.roles_modal = false
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.parmision_error = error.response.data.errors
                    }
                    this.is_parmision_error = true
                })
            },
            get_user_role: function(user_id){
                axios
                .get('users/get_role/', {

                })
                .then(Response => {
                    console.log(Response.data);
                    this.user_roles = Response.data
                })
                .catch(error => {
                    // this.user_role = "error"
                })
            },



            show_order_status_edit_madel(order_id){
                this.edit_order_modal = true
                this.get_orders_data(order_id)
            },
            show_order_detals_madel(order_id){
                this.order_detals_modal = true
                this.get_orders_data(order_id)
            },

            show_sector_model(sector_id){
                this.show_sector_modal=true

                if (this.show_sector_modal==true) {
                    axios
                    .get('/routes_and_sectors/get_routes_for_model/'+ sector_id)
                    .then(response => {
                        this.sector_routes = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );

                    axios
                    .get('/routes_and_sectors/get_sector_image/'+ sector_id)
                    .then(response => {
                        this.sector_images = response.data.sector_images
                        this.sector_images_size = response.data.sector_images_size
                    })
                    .catch(
                        error => console.log(error)
                    );

                    axios
                    .get('/routes_and_sectors/get_mtp_for_model/'+ sector_id)
                    .then(response => {
                        this.sector_mtps = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
                else{
                    this.sector_routes = ""
                    this.sector_images = ""
                    this.sector_mtp = ""
                }
            },
            show_mtp_model(mtp_id){
                this.show_mtp_modal=true

                if (this.show_mtp_modal==true) {
                    axios
                    .get('/routes_and_sectors/get_mtp_pitchs_for_model/'+ mtp_id)
                    .then(response => {
                        this.sector_mtp_pitchs_for_modal = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
                else{
                    this.sector_mtp_pitchs_for_modal = ""
                }
            },

            get_orders_data: function(id) {
                axios
                .get("../order/get_orders_data/"+id)
                .then(response => {
                    this.modal_order = response.data
                })
                .catch(error => console.log(error))
                
            },

            edit_order: function() {
                
            },

            get_routes_data: function() {
                axios
                .get("../routes_and_sectors/get_route_data")
                .then(response => {
                    this.routes = response.data
                    // console.log(this.routes.[0].id);
                })
                .catch(error => console.log(error))
            },
            get_sectors_data: function(){
                axios
                .get("../routes_and_sectors/get_sector_data")
                .then(response => {
                    this.sectors = response.data
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
            get_MTP_data: function(){
                axios
                .get("../routes_and_sectors/get_mtp_data")
                .then(response => {
                    this.MTPs = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_MTP_pitch_data: function(){
                axios
                .get("../routes_and_sectors/get_mtp_pitch_data")
                .then(response => {
                    this.MTP_pitchs = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_product_category_data: function(){
                axios
                .get("../products/get_product_category_data")
                .then(response => {
                    this.product_categorys = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_product_data: function(){
                axios
                .get("../products/get_product_data")
                .then(response => {
                    this.products = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_mount_route_data: function(){
                axios
                .get("../articles/get_article_data/mount_route")
                .then(response => {
                    this.mount_routes = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_mount_data: function(){
                axios
                .get("../mountaineering/get_mount_data")
                .then(response => {
                    this.mounts = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            save_pitchs_sequence(){
                axios
                .post('../routes_and_sectors/pitchs_sequence/', {
                    pitchs_sequence: this.sector_mtp_pitchs_for_modal,
                })
                .then((response)=> { 
                    this.show_mtp_modal = false
                })
                .catch(error =>{
                })
            },
            save_routes_sequence(){
                axios
                .post('../routes_and_sectors/routes_sequence/', {
                    routes_sequence: this.sector_routes,
                    mtp_sequence: this.sector_mtps,
                })
                .then((response)=> { 
                    this.show_sector_modal = false
                })
                .catch(error =>{
                })
            },
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